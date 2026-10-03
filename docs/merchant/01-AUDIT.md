# Audit Google Merchant Center — Phase 1 (état initial, avant modification)

Date : 2026-10-03 · Périmètre : `lenaselmolarcb-main/` · Marché ciblé : Espagne (ES, `es`, EUR)

## 1. Architecture

| Élément | Constat |
|---|---|
| Framework | Laravel 12.43.1, PHP 8.4, SQLite (`database/database.sqlite`) |
| Modèle produit | `App\Models\Product` (table `products`, 107 lignes, PK = ancien ID WooCommerce) |
| Catégories | Table `categories` présente mais **inutilisée** ; la catégorie est un slug texte dans `products.category`. Libellés dans `App\Support\CategoryLabels` |
| Marques | **Pas de modèle Brand.** Colonne `products.brand` vide pour 107/107. Marque devinée depuis le titre via `config/merchant.php` → `brands` (`BrandResolver`) |
| Variantes | Aucune. Pas d'`item_group_id`. Chaque palette/format est un produit distinct |
| Prix | `products.price` / `products.old_price` (decimal 2). `old_price` masqué par `LojaProduct` tant que `MERCHANT_REFERENCE_PRICES_VERIFIED=false` — **mais pas dans le panier ni la vue rapide** |
| Stock | Booléen `in_stock` uniquement (107/107 = en stock). Pas de quantité, pas d'état archivé/désactivé |
| Images | Chemins relatifs JSON `images` + `hover_image`, fichiers dans `public/wp-content/uploads` |
| URL produit | `/producto/{slug}` (canonical = lien du flux) |
| Traduction | Aucune (site mono-langue `es`). Contenus hérités FR/PT visibles dans les noms de fichiers d'images |
| Livraison | `config/merchant.php` → `shipping` (codé en dur, ignore les variables `.env`) + `App\Support\ShippingPolicy` (clé `publish` inexistante → toujours « no publicado ») |
| Taxes | Prix TTC (« IVA incluido »). Pas de calcul de TVA séparé |
| Panier | Session (`cart`), **copie figée du prix, du titre et de l'ancien prix** au moment de l'ajout |
| Checkout | `CheckoutController` : virement bancaire uniquement, Espagne uniquement |
| Commandes | Table `orders` + modèle `Order` existent mais **ne sont jamais écrits**. N° de commande = `rand(1000, 9999)` |
| Middlewares | `LegacyRedirects` (301 depuis `config/redirects.php`). `VerifyCsrfToken` et `HandleExpiredSession` non enregistrés (code mort) |
| Générateur XML | `App\Domain\Merchant\GoogleFeedGenerator` + `GoogleProductMapper` + `GoogleFeedValidator` + DTO `GoogleProductData` |
| Jobs | `RefreshGoogleFeedJob` (horaire) écrit le flux dans `storage/app/feeds` **et** `public/feeds` (un fichier statique masquerait la route dynamique pendant 1 h) |
| Cache | Aucun cache applicatif du catalogue ; catalogue rechargé plusieurs fois par requête |
| Données structurées | Product/Offer JSON-LD (fiche), Organization/OnlineStore/LocalBusiness (layout), BreadcrumbList |
| Sitemap | `/sitemap.xml` dynamique (OK) |
| robots.txt | Statique ; `Sitemap: /sitemap.xml` **relatif (invalide)** |
| Pages légales | Avisos legales, CGV, TCG, confidentialité, cookies, livraison, remboursement, paiement, à propos, contact |

## 2. Constat bloquant immédiat

`APP_URL=http://127.0.0.1:8000` → toutes les URL du flux sont en HTTP → le validateur existant rejette **107/107 produits** : le flux généré est **vide** dans cet environnement. En production, `APP_URL` doit être l'URL HTTPS réelle du domaine.

## 3. Problèmes critiques (peuvent empêcher l'approbation ou constituent une représentation trompeuse)

| # | Problème | Où |
|---|---|---|
| C1 | **Livraison contradictoire** : flux = 0,00 € vers toute l'Espagne en 2–3 jours ; panier/checkout = « Envío (consultar) — Gratis » ; page Contacte et vue rapide = « Envío gratuito 2 a 4 días » ; CGV = « actualmente, envío gratuito » ; Política de entrega = « no publicamos plazos ni tarifas, la cobertura se confirma por código postal » | flux, `carrinho`, `checkout`, `contacto`, `modeldetail`, CGV, política de entrega |
| C2 | **Faux prix barrés** dans le panier (`<del>{{ old_price }}</del>` pour chaque ligne) et dans la vue rapide / « Novedades » du panier vide, alors que les prix de référence ne sont pas vérifiés | `carrinho.blade.php`, `quickView`, `modeldetail` |
| C3 | **Images ne correspondant pas au produit** : #5629 « Excellent pellets » affiche une image « Ardenforest » ; #5626 « Badger » affiche une image « SunFire » ; #5643 (Nova Leña) et #5805 (Natural Energie) partagent la même image générique ; #5525 « Hunter 80B » utilise l'image « Hunter 14B » ; #5527 « Vulkan 14 kW » → image « 17 kW » ; etc. | `products.images` |
| C4 | **GTIN dupliqué** : 8437015545004 sur #31003 (70 sacs) et #31006 (40 sacs) | `products.ref` |
| C5 | **Marques fabriquées** : des noms de **modèle** sont envoyés comme marque (`SAMANTHA`, `BESTIA`, `MATILDE`, `VEGA`, `ELITE`, `CERO rem`) et `BP-100`/`BP-CH0`/`BP-402` → « FM » sans que « FM » figure dans le titre | `config/merchant.php` → `brands` |
| C6 | **Même produit, deux prix** : Valboval 65 sacs (#5633 350 € / #5802 374 €), Limouzi 66 sacs (#5631 439 € / #5804 405 €), Excellent 65 sacs (#5629 320 € / #5803 410 €) | catalogue |
| C7 | Le panier n'empêche pas l'ajout d'un produit « Agotado » (contrôle uniquement visuel) | `HomeController::addToCart` |
| C8 | **Paiement impossible à finaliser** : `BANK_IBAN` vide → ni la confirmation ni l'e-mail n'affichent de coordonnées bancaires alors que le virement est le seul moyen de paiement | `.env`, `config/bank.php` |
| C9 | **Identité / domaine** (résolu le 2026-10-03 : domaine canonique lenaselmolar.es) : `.htaccess` redirige tout vers `casacubertatrias.es` alors que l'entreprise est « Leñas El Molar C.B. » et que l'e-mail d'envoi est `@lenaselmolar.es` | `.htaccess`, `.env` |
| C10 | Commandes non enregistrées et numéro aléatoire non unique | `CheckoutController` |

## 4. Problèmes importants

- `identifier_exists=no` jamais envoyé quand une marque est devinée → produits de marque sans GTIN/MPN signalés « identifiant manquant ».
- SKU incohérent : JSON-LD `sku = lv-{id}`, page « REF: 5374xxxx ».
- Titre du panier ≠ titre de la page/flux (le panier copie le titre brut).
- Catégorie incohérente : #5637–#5641 « Madera densificada » classés dans `lena`.
- Titres traduits de marques : #5635 « Pellets de energía natural » (image « Natural-Energie »), #5636 « Pellets de crema premium » (image « crépito »).
- Allégations de certification non documentées dans des titres (« calidad DIN Plus », « certificados ») alors qu'aucun certificat n'est documenté (`App\Support\Certifications`).
- Prix unitaire (RD 3423/2000) absent : `unit_measure_value` vide pour 107/107.
- EPREL : `eprel_code` vide pour les 39 poêles/chaudières (étiquette énergétique UE).
- 24 images < 500×500 px ; images provenant de places de marché (`_AC_SL1500_`, `images-1.jpg`, `WeChatImage…`) → droits et pertinence à vérifier.
- Liens sociaux factices (`https://www.facebook.com/`, `https://www.instagram.com/`) et lien e-mail `href="#"` dans le widget de contact.
- Libellé CNAE en français (« Sciage et rabotage du bois ») sur des pages en espagnol.
- Aucun e-mail public (seulement téléphone/WhatsApp/formulaire) alors que les commandes sont envoyées depuis `contacto@lenaselmolar.es`.
- Page « Sobre nosotros » : décrit une activité locale de leña et carbón à El Molar, alors que le catalogue vend surtout des poêles, chaudières et pellets de nombreuses marques.
- Flux : écriture d'un fichier statique `public/feeds/google-shopping.xml` qui masquerait la route dynamique (risque de flux obsolète).

## 5. Avertissements

- Catalogue rechargé plusieurs fois par requête (provider + chaque `LojaProduct::query()`).
- Case « acepto los términos » non validée côté serveur.
- Pas de limitation de débit sur `/contacto` et `/checkout`.
- `merchant.shipping` ignore les variables `MERCHANT_SHIPPING_*` déjà présentes dans `.env`.

## 6. Ce qui est déjà correct

- Mapping de disponibilité strict (`in_stock` / `out_of_stock`), enum PHP.
- Validation GTIN (longueur, checksum, préfixes 2xx internes rejetés, jamais généré).
- MPN refusé quand il n'est qu'un clone de l'ID ou un code-barres.
- Prix au format `29.99 EUR`, XML DOM + CDATA, revalidé par un parseur.
- Gate `reference_prices_verified` sur le flux, le JSON-LD et les fiches.
- Certifications publiées uniquement si numéro + validité documentés.
- IDs de catégorie Google vérifiés contre la taxonomie officielle (version 2021-09-21) : 625, 2639, 3082 existent et correspondent.
