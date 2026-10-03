# Google Merchant Center — Rapport final

Date : 2026-10-03 · Marché : Espagne (ES · `es` · EUR) · État initial : voir `01-AUDIT.md`

> Aucune approbation Google n'a été obtenue ni n'est garantie. Ce rapport décrit l'état technique du site et du flux, pas une décision de Google.

## 1. Architecture analysée et résultat

Principe appliqué : **une seule source**, `App\Domain\Catalog\Catalog`, lue par la page produit, le JSON-LD, le panier, le checkout, les e-mails et le flux.

```
products (DB) ──► Catalog (1 chargement/requête, règle prix de référence, is_active)
                    ├─► ProductPresenter (titre, SKU lv-{id}, GTIN vérifié, MPN)
                    ├─► GoogleProductMapper ─► GoogleProductData ─┬─► page produit (H1, prix, stock)
                    │                                             ├─► JSON-LD Product/Offer
                    │                                             └─► flux XML
                    ├─► Cart (session = id + quantité ; prix relu à chaque accès) ─► Checkout ─► Order
                    └─► config('loja_products') (pages historiques : vue rapide, landing, wishlist)
ShippingPolicy ──► page, panier, checkout, CGV, política de entrega, contacto, JSON-LD, flux
CatalogValidator ─► exclusions du flux + merchant:validate ; ConsistencyChecker ─► merchant:feed-test
```

## 2. Fichiers et tables analysés

Tous les fichiers de `app/`, `config/`, `routes/`, `database/`, `resources/views/` (pages, partials, e-mails), `.env` (secrets masqués), `.htaccess`, `public/robots.txt`, `tests/`. Tables : `products` (107 lignes), `categories` (inutilisée), `orders` (vide), `sessions`, `cache`, `jobs`. Taxonomie Google officielle (version 2021-09-21) téléchargée dans `resources/merchant/`.

## 3. Résultats des tests

| Vérification | Résultat |
|---|---|
| `php artisan test` | **51 tests OK** (163 assertions), dont 22 nouveaux |
| `merchant:validate` (APP_URL = https://lenaselmolar.es) | 107 produits · **95 valides · 12 bloqués** · 15 CRITICAL (13 produit + 2 compte) · 84 IMPORTANT · 242 WARNING |
| `merchant:feed-test` (même APP_URL) | XML valide, UTF-8, RSS 2.0, namespace `g:` OK · 95 articles · **0 incohérence** BASE ↔ PAGE ↔ SCHEMA ↔ FLUX ↔ PANIER · **échec volontaire** sur 2 CRITICAL de compte (IBAN, livraison) |
| Domaine (mise à jour) | `.htaccess`, `APP_URL`, sitemap et robots.txt pointent vers `https://lenaselmolar.es` : 0 erreur URL |
| Smoke test de toutes les routes publiques | 200 partout (checkout vide → 302, page inconnue → 404, `/certificaciones` → 404 voulu tant qu'aucun certificat n'est documenté) |
| Produits représentatifs (#29106 avec GTIN, #5621 sans GTIN avec marque, #5810 6 images, #5806 vendu au m³) | titre, prix, devise, disponibilité, SKU, GTIN, marque identiques en base, page, JSON-LD, panier et flux |

Le catalogue ne contient ni variante, ni promotion publiable, ni produit hors stock : ces cas sont couverts par les tests automatiques (`GoogleFeedTest`, `CartCheckoutTest`).

## 4. Corrections automatiques effectuées

**Cohérence des prix et du panier**
- Le panier ne stocke plus que l'ID et la quantité. Le prix est relu dans le catalogue à chaque affichage, au checkout et à l'enregistrement de la commande.
- Un produit hors stock ne peut plus être ajouté (erreur 409), et un panier contenant un produit devenu indisponible bloque le checkout.
- Faux prix barrés supprimés du panier, de la vue rapide et des suggestions du panier vide.

**Livraison**
- Une seule source de vérité (`ShippingPolicy`) relit enfin les variables `.env`.
- Aucun « Gratis / 2 a 4 días » n'est publié tant que le tarif **et** la couverture ne sont pas confirmés. Tant que ce n'est pas le cas, le flux n'envoie pas de `g:shipping` et le JSON-LD n'a pas de `shippingDetails`.

**Commandes**
- Chaque commande est enregistrée dans `orders` avec un numéro séquentiel `LEM-000001`.
- La case CGV est validée côté serveur.
- Le pays de livraison est limité à ES.
- Les logs ne contiennent plus de données personnelles.

**Identifiants (GTIN, MPN, marque)**
- Nouvelles colonnes `gtin` et `mpn`, réservées aux identifiants vérifiés. Le champ `ref` n'est plus jamais envoyé comme MPN.
- `identifier_exists=no` est envoyé quand il n'y a ni GTIN ni MPN ; la marque réelle reste envoyée.
- Marques fabriquées retirées : noms de modèle (SAMANTHA, BESTIA, MATILDE, VEGA, ELITE, CERO, CHIP2, Temy, Moravia, Hunter, Vulkan, Olimpia) et marque « FM » déduite des références BP-xxx.

**Contrôle du flux**
- Mécanisme d'exclusion contrôlé : `is_active`, `merchant_excluded` + `merchant_exclusion_reason`, et blocage automatique des produits ayant une erreur CRITICAL. Chaque exclusion est écrite dans `storage/logs/merchant-*.log`.
- Le flux n'écrit plus de copie statique dans `public/feeds` (elle aurait masqué la route dynamique). Cache réduit à 5 min, et `X-Robots-Tag: noindex` sur le flux.

**Textes et données produit**
- Nettoyage des titres et descriptions : `<script>`/`<style>`, caractères invisibles ou invalides en XML.
- Prix unitaires (RD 3423/2000) remplis pour 45 produits, à partir des quantités écrites dans leur titre. Les estéreos ne sont pas convertis en m³, et le produit #5716 n'a pas été rempli car sa quantité et son prix sont incompatibles.

**Page produit**
- SKU, EAN, marque, quantité et bloc livraison/retours affichés exactement comme dans le flux.
- Fil d'Ariane avec URL.
- « Las mejores ofertas » remplacé par « IVA incluido ».
- « Pago SEGURO garantizado » remplacé par « Pago por transferencia bancaria ».

**Site**
- Liens Facebook/Instagram factices supprimés (ils ne s'affichent que si une URL réelle est configurée).
- Lien e-mail `href="#"` redirigé vers le formulaire de contact.
- Bloc « Vistos recientemente » factice retiré.
- **Bug préexistant corrigé** : `/politica-de-entrega` renvoyait une **erreur 500** (route inexistante).
- `robots.txt` généré avec une URL de sitemap absolue.
- Limitation de débit sur le contact, le checkout et l'ajout au panier.

**Divers**
- Le tri par prix était alphabétique (« 2499 » < « 320 ») : corrigé.
- Les bornes du filtre de prix codées en dur (110–2 997 €) sont maintenant calculées depuis le catalogue.
- Libellé CNAE traduit en espagnol.
- Messages de la wishlist traduits du français vers l'espagnol.

## 5. Produits exclus du flux (12) et raisons

| ID | Produit | Raison |
|---|---|---|
| 5626 | Pellet Badger 65 sacs | La galerie contient une photo « SunFire » (autre marque) |
| 5629 | Excellent pellets 65 sacs | Photo « Ardenforest » dans la galerie + même article que #5803 à un autre prix |
| 5803 | Excellent pellets 65 sacs | Même article que #5629 : 410 € contre 320 € |
| 5631 / 5804 | Limouzi 66 sacs | Même article : 439 € / 405 € |
| 5633 / 5802 | Valboval 65 sacs | Même article : 350 € / 374 € (le titre de #5802 contient « (ref. 53745802) ») |
| 5643 / 5805 | Nova Leña / Natural Energie | Photo identique (même fichier) pour deux marques différentes |
| 5716 | Palser pellet 15 kg | 366 € pour 15 kg = 24,40 €/kg : la quantité du titre ou le prix est faux |
| 31003 / 31006 | Naturpellet 70 et 40 sacs | Même GTIN 8437015545004 sur deux articles différents |

Ces produits restent en vente sur le site. Ils réintègrent le flux automatiquement dès que la donnée est corrigée.

## 6. Corrections nécessitant une intervention humaine

**Bloquantes (CRITICAL)**
1. **IBAN** : renseigner `BANK_IBAN` (et `BANK_BIC`, `BANK_NAME`). Aujourd'hui le client ne peut pas payer.
2. **Livraison** : fixer le vrai tarif, les vrais délais et la vraie couverture. Les textes du site se contredisaient : les CGV et la page Contact disaient « gratuit », la Política de entrega disait « pas de tarif, couverture selon le code postal ». Si les Baléares, les Canaries, Ceuta et Melilla ne sont pas desservies, configurer des régions dans Merchant Center. Ensuite seulement, activer `MERCHANT_SHIPPING_COVERAGE_CONFIRMED=true`.
3. **Domaine — résolu** : domaine canonique `lenaselmolar.es` (`.htaccess` + `APP_URL`), cohérent avec le nom de l'entreprise et l'e-mail `@lenaselmolar.es`. Reste à faire côté serveur/Google : certificat HTTPS valide sur `lenaselmolar.es`, faire pointer les anciens domaines (casacubertatrias.es, lenhaviva.*) vers ce serveur pour que leurs 301 fonctionnent, revendiquer `lenaselmolar.es` dans Merchant Center et Search Console.
4. Les 12 produits ci-dessus : remplacer les photos d'une autre marque, désactiver ou différencier les doublons, corriger le GTIN de Naturpellet et le titre ou le prix de #5716.

**Importantes**
- 5 codes EAN de la base ont une **clé de contrôle invalide** (#29115, #31002, #31007, #31008, #31009) et #31005 a un code interne en 2xx. Ils ne sont pas envoyés ; il faut obtenir les vrais codes.
- 13 GTIN repris du champ `ref` (fournisseur) : vérifier sur l'emballage s'il s'agit du code du sac ou de la palette, puis les copier dans `products.gtin`.
- 42 marques déduites du titre : à confirmer dans `products.brand`. 39 poêles et chaudières n'ont pas de marque fiable (nom de modèle seulement).
- Allégations « DIN Plus », « ENplus » et « certificados » dans des titres et descriptions sans certificat documenté : fournir le numéro de certificat du fabricant ou retirer la mention.
- Photos réutilisées d'un modèle à l'autre (chaudières 20/25/35/40 kW, Hunter 14B/80B, bois 30/33/45 cm, etc.) et photos d'origine douteuse (Amazon, `images-1.jpg`, export PrestaShop) : remplacer par les photos exactes.
- #5637–#5641 « Madera densificada » sont classés dans la catégorie `lena`.
- Titres traduits de marques : #5635 « energía natural » (vraie marque : Natural Energie ?), #5636 « crema » (Crépito ?).
- Poêles et chaudières : étiquette énergétique UE et code EPREL à obtenir et afficher (`products:set-eprel`).
- 6 produits vendus en estéreos ou à la palette sans quantité convertible : renseigner manuellement leur prix unitaire.
- Identité : publier un e-mail de service client (`merchant.nap.email`) ; réécrire « Sobre nosotros », qui décrit une activité locale de bois et charbon alors que le catalogue vend surtout des poêles, chaudières et pellets de marques tierces.
- Configurer le cron `php artisan schedule:run` sur le serveur (snapshot horaire du flux).

## 7. Points à vérifier dans Merchant Center (non vérifiables depuis le code)

- Revendication et vérification du domaine ; HTTPS et certificat valides en production.
- Paramètres de livraison et de retours au niveau du compte, identiques à `ShippingPolicy` et à la politique de remboursement (14 jours, retour à la charge du client, exceptions pour le vrac).
- Diagnostics après le premier fetch : GTIN, identifiants, images, prix détectés sur la page (crawl), « mismatched value ».
- Coordonnées de l'entreprise dans Merchant Center = NAP du site (nom, adresse El Molar, téléphone +34 679 24 55 97, NIF E85899003).
- Test réel depuis la production : `php artisan merchant:feed-test --http` (vérifie le flux public et chaque URL/image sans redirection).
- Parcours mobile complet (accueil → catégorie → produit → panier → checkout) sur un vrai téléphone : seul un contrôle statique a été fait ici (viewport, pages 200).
- Risque de politique « misrepresentation » à évaluer honnêtement : paiement **uniquement** par virement, prix de pellets bas, photos d'origine tierce. Ce profil est souvent examiné de près par Google. Les corrections ci-dessus réduisent ce risque mais ne l'éliminent pas. Un moyen de paiement offrant une protection à l'acheteur aiderait.

## 8. Commandes ajoutées

| Commande | Rôle |
|---|---|
| `php artisan merchant:validate [--severity=critical] [--group=IMAGE] [--product=ID] [--summary]` | Rapport complet par produit (ID, SKU, nom, champ, valeur, attendu, gravité, raison, correction) + JSON `storage/app/merchant/validation-report.json`. Code de sortie 1 s'il y a des CRITICAL |
| `php artisan merchant:feed-test [--http] [--no-consistency]` | Génère le flux par la vraie route, le parse, vérifie structure, doublons, champs, URL, prix, images, disponibilité, langue, catégories, identifiants et livraison, puis compare chaque produit entre base, page, JSON-LD, flux et panier. Code de sortie 1 s'il y a des CRITICAL |
| `php artisan merchant:google-feed` | Snapshot du flux dans `storage/app/feeds` (plus dans `public/`) |

## 9. Fichiers modifiés / ajoutés

**Ajoutés**
- `app/Domain/Catalog/{Catalog,ProductPresenter}.php`
- `app/Domain/Cart/{Cart,CartException}.php`
- `app/Domain/Merchant/Validation/{CatalogValidator,ConsistencyChecker,Issue,Severity,ValidationReport}.php`
- `app/Console/Commands/Merchant/{ValidateMerchantCommand,FeedTestCommand}.php`
- `database/migrations/2026_10_03_000000_add_merchant_control_columns_to_products.php`
- `resources/views/partials/mini-cart.blade.php`
- `resources/views/robots.blade.php`
- `resources/merchant/taxonomy-with-ids.es-ES.txt`
- `tests/Feature/CartCheckoutTest.php`
- `tests/Feature/Merchant/MerchantCommandsTest.php`
- `docs/merchant/01-AUDIT.md`, `docs/merchant/02-RAPPORT-FINAL.md`

**Modifiés**
- Domaine Merchant :
  - `GoogleProductMapper`, `GoogleFeedGenerator`, `GoogleFeedValidator`
  - `Support/{ProductImage,TitleBuilder}`
  - `DTO/Merchant/GoogleProductData`
- Contrôleurs :
  - `HomeController` (réécrit)
  - `CheckoutController` (réécrit)
  - `WishlistController`
  - `Merchant/GoogleFeedController`
- Code de support :
  - `Repositories/LojaProduct`
  - `Providers/CatalogServiceProvider`
  - `Support/{ShippingPolicy,UnitPricing}`
  - `Models/Product`
  - commandes `BackfillUnitMeasure`, `GenerateGoogleFeedCommand`, `AuditGoogleFeedCommand`
- Configuration :
  - `config/{merchant,logging}.php`
  - `routes/web.php`
  - `.env.example`
- Vues :
  - `products/show`, `carrinho`, `checkout`, `checkout/confirmation`
  - `section/modeldetail`
  - `pages/{contacto,condicoes-gerais-de-venda-cgv,politica-de-entrega}`
  - `layouts/app`, `layouts/partials/footer/public`, `layouts/partials/navbar/{public,public-show}`
- Tests :
  - `tests/Feature/Merchant/GoogleFeedTest`
  - `tests/Unit/{UnitPricingTest,Merchant/GoogleProductMapperTest}`
  - `database/factories/ProductFactory`

**Retiré**
- `public/robots.txt`, remplacé par la route dynamique (une copie de l'ancien fichier a été conservée dans `%TEMP%\robots.txt.old`).

**Données**
- Migration additive.
- `unit_measure_value`/`unit_measure_unit` remplis pour 45 produits.
- Sauvegarde de la base avant modification : `%TEMP%\database.backup-before-merchant.sqlite`.
