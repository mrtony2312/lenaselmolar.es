# Google Merchant Center — Marcas, GTIN/MPN et prix barrés

Date : 2026-10-04 · Catalogue : 228 produits (107 `config/loja_products.php` + 121 `config/pellets_hogar_products.php`, confirmé = 228 URLs du sitemap) · Marché : Espagne (ES · `es` · EUR) · Fuseau promo : Europe/Madrid.

> Ce rapport documente ce qui a réellement été vérifié dans le code. Aucune marque, aucun GTIN et aucun MPN n'est inventé : tout ce qui n'est pas confirmé par le brief fourni reste vide et figure dans la liste « à confirmer fournisseur ».

## 1. Corrections de code appliquées

| # | Fichier | Changement | Raison |
|---|---|---|---|
| 1 | `app/Domain/Catalog/ProductPresenter.php` | Suppression du repli `ref → gtin` (le code n'envoie plus jamais le champ `ref` comme EAN, même si son checksum est valide) | Aucun EAN de ce catalogue n'est confirmé sur emballage/facture ; un checksum valide ne prouve pas que le code a été lu sur l'unité vendue (cf. doublon `8437015545004` sur deux conditionnements Naturpellet différents, et le code `8023857870192` recopié d'une fiche jumelle Edilkamin) |
| 2 | `config/merchant.php` (`brands`) | Table de marques réécrite : ajout des marques confirmées par le brief (KVS Moravia, Termomont, FM Calefacción, FreePoint, Sannover, Redpod, Eider Biomasa, Weber, Burpellet, CYL Pellet corrigé, Huella Verde, Pellet Asturias, Helios généralisé, Badger généralisé, Ecopower, Excellent Pellets corrigé) ; **retrait** des marques non sourcées qui étaient envoyées à tort (Natural Energie, Naturkraft, MM Royal, Proxima Star, Bio Energy, Green Energy, DIN Pellets, Nova Leña, Mi Pellet, Pellet Gold, Pellet Bear, Valboval, Palser) | brand doit être le fabricant/la marque réelle, jamais une marque non confirmée ni « Leñas El Molar »/« Boutique »/« Generic » (déjà absent par défaut — `MERCHANT_DEFAULT_BRAND` est vide) |
| 3 | `resources/views/home.blade.php` | Les 7 carrousels d'accueil (leña, pellets, chef, compactada, caldeira, granel, madeira/fogo) affichaient le badge « Oferta » et un prix barré **sans aucune condition**. Ajout de `@if(config('merchant.reference_prices_verified') && old_price > price)` autour du badge et du `<del>` | Un barré à `0,00 €` (et tout autre barré non vérifié ≥ 30 jours) est interdit ; ces blocs lisaient `LojaProduct::query()` (déjà filtré par `Catalog`) mais le HTML les affichait sans relire la condition — corrigé pour ne jamais afficher de barré tant que `MERCHANT_REFERENCE_PRICES_VERIFIED` n'est pas vrai |
| 4 | `resources/views/category.blade.php` | `<title>`/`<h1>` ne disent plus « a domicilio en España » (déjà traité le 2026-10-03, rappelé ici pour contexte) | — |

Ces 3 fichiers de code (1, 2, 3) sont la correction structurelle demandée par cette mission. Aucune suppression de fonctionnalité, aucun autre fournisseur touché, aucune donnée de prix modifiée.

**Vérifié** : suite de tests complète relancée (`php artisan test`) — 51/51 tests passent avec un environnement complet ; aucune régression par rapport à l'état avant modification.

## 2. Marques — résultat

Sur 228 produits : **109 ont une marque confirmée** résolue automatiquement par titre (mécanisme `BrandResolver`, inchangé dans sa logique, seule la table de correspondance a été corrigée) ; **119 restent sans marque** (`brand` vide, `identifier_exists=no`), listés en section 4.

Exemples vérifiés contre le brief :
- `lv-5508` Moravia 9114 → **KVS Moravia**, prix 1497.00 EUR ✓ (correspond exactement au brief)
- `lv-5509` MBS Magnum → **MBS**, prix 1797.00 EUR ✓
- `lv-5621` Woodstock → **Woodstock**, prix 320.00 EUR ✓
- `lv-902261` FreePoint Roxy 7 kW → **FreePoint**
- `lv-902266` Eider Biomasa Watt 7kw → **Eider Biomasa**
- `lv-29142` Edilkamin Chip2 Plus → **Edilkamin**
- `lv-902502` FM CH-4 R → **FM Calefacción**

Marques laissées explicitement vides malgré un nom de modèle dans le titre (ambiguïté reconnue par le brief, plaque/fiche non lue) : Hunter 14B/80B, Olimp/Olimpia/Olympia, Vulkan/Thermo Vulkan(e), Samantha, Eclipse, Diva, Eco Mensa, Krone, Bestia, Matilde, Vega, calderas et estufas anonymes.

**Risque résiduel signalé** (needles à surveiller si de nouveaux produits sont ajoutés au catalogue) :
- `Watt` → Eider Biomasa : mot générique, sûr aujourd'hui (seuls les 4 modèles Eider Biomasa du catalogue le contiennent) mais à resserrer si un autre produit mentionne un jour une puissance en « watt » dans son titre.
- `Asturias` → Pellet Asturias : nom de région, même remarque.

**Découverte non couverte par le brief** — trois chaînes qui ressemblent à des marques imprimées sont présentes dans `pellets_hogar_products.php` sans qu'aucune instruction ne les confirme : **« LEÑAS OLIVER »** (`lv-902588`, `lv-902581`, `lv-902501`), **« LEÑAS LEGUA »** (`lv-902500`), **« BIOENERGY »** (`lv-902499`). Elles n'ont pas été ajoutées à la table de marques (ne rien publier de non sourcé) mais méritent une vérification fournisseur rapide — elles sont incluses dans la liste à confirmer.

## 3. GTIN / MPN

**Aucun GTIN ni MPN n'est envoyé pour les 228 produits** dans cette passe : aucun n'est confirmé sur emballage/facture dans le brief fourni. `identifier_exists=no` est donc correct dès qu'un produit n'a ni GTIN ni MPN — y compris pour les 109 produits qui ont désormais une marque (une marque seule n'est pas un identifiant unique).

Le code a été corrigé (section 1.1) pour ne plus jamais recycler le champ `ref` (SKU fournisseur / EAN de palette WooCommerce) comme GTIN. Cela évite en particulier de renvoyer :
- `8437015545004` (ref dupliquée sur `lv-31003` 70 sacs et `lv-31006` 40 sacs — deux conditionnements différents, deux GTIN réels différents) ;
- `8023857870192` (ref copiée d'une fiche jumelle Edilkamin, non vérifiée sur la plaque de l'unité vendue) ;
- tout autre `ref` à 13 chiffres des références `29xxx`/`31xxx`, qui sont des codes internes/fournisseur non vérifiés.

Les MPN probables (ex. `9114` pour KVS Moravia, `BP-100` pour FM Calefacción, `CHIP2 PLUS` pour Edilkamin, `TEMY PLUS P 20` pour Termomont, `Roxy` pour FreePoint) ne sont **pas** publiés non plus : ce sont des codes modèle déduits du titre, pas confirmés comme la référence fabricant exacte de l'unité vendue. Ils figurent dans la liste « à confirmer fournisseur ».

## 4. Prix barrés

Règle appliquée partout (feed, JSON-LD, panier, checkout, page produit **et** carrousels d'accueil, désormais alignés) : un prix barré n'est jamais affiché tant que `MERCHANT_REFERENCE_PRICES_VERIFIED=false`. C'est l'état actuel (pas de preuve documentée qu'un prix a été payé ≥ 30 jours avant une remise ≤ 30 jours). Conséquence :
- Le CSV livré (section 5) ne contient **aucun** `sale_price` ni `sale_price_effective_date` : une seule colonne `price` (le prix TTC affiché), pour tous les produits.
- Le badge « Oferta » et le prix à 0,00 € constaté sur Woodstock/Helios/MM Royal/Proxima Star/Ardenforest en page d'accueil ne peuvent plus s'afficher (corrigé, section 1.3).
- Si une vraie promotion datée existe un jour (prix réellement payé ≥ 30 jours, remise 5–90 %, durée ≤ 30 jours), il faudra : documenter la date de début du prix de référence, activer `MERCHANT_REFERENCE_PRICES_VERIFIED=true`, et renseigner `sale_price_effective_date` au format ISO 8601 avec le fuseau Europe/Madrid (ex. `2026-10-04T00:00:00+02:00/2026-11-03T23:59:59+01:00`) — le code lit déjà ce flag partout, rien d'autre à coder.

## 5. Livrable CSV

Fichier : [`03-brand-gtin-corrections.csv`](./03-brand-gtin-corrections.csv) — 228 lignes, colonnes `id,title,brand,gtin,mpn,identifier_exists,price,sale_price,sale_price_effective_date`.

`id` = SKU interne (`lv-xxxx`), jamais envoyé comme `gtin`/`mpn` ailleurs. `gtin`/`mpn`/`sale_price`/`sale_price_effective_date` sont vides pour les 228 lignes, pour les raisons ci-dessus.

## 6. À confirmer fournisseur (119 produits sans marque + tout GTIN/MPN)

Aucun GTIN et aucun MPN fabricant n'est publié pour l'instant : les **228** produits ont besoin d'une confirmation d'identifiant si on veut sortir du mode `identifier_exists=no`. Ci-dessous, les **119** produits qui restent aussi sans marque (nom de modèle ambiguïté, producteur pellets non confirmé, ou bois/densifié générique) :

| SKU | Titre |
|---|---|
| lv-5522 | Caldera de leña maciza 40 kW |
| lv-5519 | Caldera de leña sólida 25 kW |
| lv-5521 | Caldera de leña sólida 35 kW |
| lv-5523 | Caldera de leña 50 kW sólida |
| lv-5518 | Caldera de leña maciza de 20 kW |
| lv-5520 | Caldera de leña maciza de 30 kW |
| lv-5514 | Estufa de leña Olimpia |
| lv-5527 | Estufa de leña Vulkan 14 kW |
| lv-5524 | Estufa de leña Hunter 14B |
| lv-5525 | Estufa de leña Hunter 80B |
| lv-5533 | Estufa de leña Olimp |
| lv-5528 | Estufa de leña Olympia |
| lv-5535 | Estufa de leña Thermo Vulkan plus |
| lv-5534 | Estufa de leña con caldera Thermo Vulcan de 18 kW |
| lv-5529 | Estufa de leña de esteatita Olymp |
| lv-5532 | Cocina Olimpia de esteatita |
| lv-5630 | Green Energy pellet – palé de 65 sacos de 15 kg |
| lv-5822 | Leña – 25 cm – alto rendimiento – 1,3 m³ |
| lv-5817 | Leña – 30 cm – alto rendimiento – 2 m³ |
| lv-5821 | Leña – 30 cm – alto rendimiento – 1,3 m³ |
| lv-5818 | Leña – 33 cm – mezcla de maderas duras – palé 2 m³ – 2,9 estéreos |
| lv-5823 | Leña – 40 cm – alto rendimiento – 1,3 m³ |
| lv-5820 | Leña – 40 cm – alto rendimiento – 2 m³ |
| lv-5819 | Leña – 50 cm – mezcla de maderas duras – palé 2 m³ – 2,5 estéreos |
| lv-5811 | Leña para calefacción – 40 cm – 2 m³ – 1,85 estéreos por camión completo |
| lv-5816 | Leña – 25 cm – alto rendimiento – 2 m³ |
| lv-5806 | Leña – 40 cm – alto rendimiento – 1,3 m³ – 1,25 estéreos |
| lv-5638 | Madera densificada – madera dura – 1 tonelada por palé |
| lv-5639 | Madera densificada – madera dura + madera blanda – 1/2 palé de 480 kg |
| lv-5640 | Madera densificada – madera dura + madera blanda – palé de 960 kg |
| lv-5641 | Madera densificada – madera blanda – palé de 960 kg |
| lv-5637 | Madera densificada – troncos nocturnos – 1/2 palé de 480 kg |
| lv-5809 | Madera densificada – palé de 96 paquetes de 5 troncos |
| lv-5810 | Madera densificada – calidad superior – palé de 80 cajas de 6 troncos |
| lv-5807 | Madera densificada – sin envase de plástico individual – 1/2 palé de 480 kg |
| lv-5808 | Madera densificada – sin envase de plástico individual – palé de 960 kg |
| lv-5815 | Troncos de calefacción – 33 cm – seco – 1,7 m³ – 1,9 estéreos |
| lv-5614 | Palé de leña – 25 cm – 3 estéreos |
| lv-5615 | Palé de leña – 33 cm – 3 estéreos |
| lv-5616 | Palé de leña – 40 cm – 3 estéreos |
| lv-5617 | Palé de leña – 45 cm – 3 estéreos |
| lv-5812 | Palé de leña (1,7 m³) por camión |
| lv-5814 | Palé de pellets DIN Plus (65 sacos) por carga de camión |
| lv-5623 | Palé de pellets MM Royal – 78 sacos plásticos |
| lv-5624 | Palé de pellets Proxima Star |
| lv-5813 | Palé extra de pellets (65 sacos) por camión completo |
| lv-5716 | Palser pellet 15 kg |
| lv-5799 | Pellet Bear – palé de 65 sacos de 15 kg |
| lv-5627 | Pellet Bio Energy – palé de 66 sacos de 15 kg |
| lv-5628 | Pellet DIN Pellets – palé con 65 sacos de 15 kg |
| lv-5801 | Pellet Gold – palé de 65 sacos de 15 kg |
| lv-5805 | Pellet Natural Energie – palé de 65 sacos de 15 kg |
| lv-5643 | Pellet Nova Leña – 77 sacos de 15 kg |
| lv-5633 | Pellet Valboval – palé de 65 sacos de 15 kg |
| lv-5802 | Pellet Valboval – palé de 65 sacos de 15 kg |
| lv-5636 | Pellets de crema premium |
| lv-5635 | Pellets de energía natural – palé con 70 sacos de 15 kg |
| lv-5798 | Pellets Naturkraft – palé de 33 sacos de 15 kg |
| lv-5800 | Pellets Naturkraft – palé de 66 sacos de 15 kg |
| lv-5618 | Troncos 50 cm: 4 palés – 2 de roble blanco + 2 de haya/carpe |
| lv-5620 | Troncos de roble blanco – 50 cm (2 estéreos) |
| lv-5619 | Troncos de 50 cm: 2 palés: 1 de roble blanco + 1 de haya/carpe |
| lv-29120 | Estufa de pellets – modelo VEGA 10kw |
| lv-29115 | Estufa de pellets – modelo MATILDE HIERRO FUNDIDO |
| lv-29110 | Estufa de pellets – modelo BESTIA 15,3kw triple salida |
| lv-29106 | Estufa – cocina de pellets – modelo SAMANTHA inox |
| lv-31005 | Mi Pellet – pellets premium certificados, 77 sacos |
| lv-31008 | Palet de pellets – 70 sacos (1.050 kg) |
| lv-902779 | Troncos de 50 cm, 1,5 steres, 1,2 m³ MINUCCI |
| lv-902778 | Palé de leña – 50 CM – 3 unidades |
| lv-902777 | Palé de leña – 33 cm – 3 unidades |
| lv-902776 | Palé de leña – 40 cm – 3 metros cúbicos |
| lv-902775 | Troncos comprimidos de madera dura 100% – Palé de 1040 kg |
| lv-902774 | Madera densificada – Madera blanda – Palé de 960 kg |
| lv-902773 | Madera densificada; palé de madera dura; 1 tonelada |
| lv-902772 | Madera densificada – Maderas duras – 1/2 palé de 538 kg |
| lv-902686 | PELLET SUNFIRE – PALETTE DE 70 SACS DE 15 KG |
| lv-902684 | Pellets para tejones – 1/2 palé de 35 bolsas de 15 kg |
| lv-902683 | Palé de leña – 45 cm – 3 metros cúbicos |
| lv-902682 | Pellets de molino de energía de madera – Palé de 65 sacos de 15 kg |
| lv-902679 | LEÑA – 30 CM – 1 M3 PALÉ – 1,5 STERES |
| lv-902677 | LEÑA – 45 CM – MEZCLA DE MADERA DURA – PALÉ DE 2 M³ – 2,6 STERES |
| lv-902676 | Palet de madera de haya pura – 33 CM – 3 unidades |
| lv-902675 | 4 – Palets – Troncos de 40 cm |
| lv-902674 | Leña, roble ultraseco, palets |
| lv-902673 | Troncos densificados nocturnos: madera comprimida de larga duración |
| lv-902672 | MEDIO PALÉ – 25 CM – MADERAS DURAS MIXTAS – 1 PALÉ M3 – 1,5 STERES |
| lv-902671 | Medio palé – Fresno secado al horno – 25 cm |
| lv-902670 | Palé grande – 100 % carpe secado al horno – 25 cm |
| lv-902669 | Pellets de energía verde – Palé de 65 sacos de 15 kg |
| lv-902668 | Pellets DS Energies – Palé de 65 sacos de 15 kg |
| lv-902667 | Pellets de excelente calidad – Palé con 65 sacos de 15 kg. |
| lv-902666 | Troncos de 50 cm – 4 palets: 2 de roble blanco + 2 de haya/carpe |
| lv-902665 | Troncos de roble blanco – Haya – 25 cm |
| lv-902664 | Pellets de madera premium Total Pellet – Palé de 990 kg |
| lv-902662 | Palé de pellets MM ROYAL – 78 bolsas de plástico |
| lv-902661 | PALÉ DE PELLETS PROXIMA STAR |
| lv-902660 | PELLETS DE GUIJUELA CHASE – 100% madera blanda, 72 bolsas |
| lv-902659 | 40 sacos de madera de aliso – 2 m³ – 25 cm |
| lv-902595 | 15 sacos de madera de abedul 100% seca, 8 kg cada uno – 25 cm |
| lv-902593 | Pellets de madera alemanes EN+A1 |
| lv-902592 | 2 palets de pellets alemanes EN+A1 |
| lv-902591 | Palet de 252 Bolsas de Briquetas de Carbón Vegetal de 3 kilos – 756 Kilos |
| lv-902588 | Palet de briquetas de madera LEÑAS OLIVER de 890 kg |
| lv-902582 | Palet de briquetas de carbón vegetal – 756 kg |
| lv-902581 | Palet de briquetas de madera LEÑAS OLIVER – 890 kg |
| lv-902580 | Pack de 20 briquetas de encina de 7 kg + caja de Wood Balls |
| lv-902579 | Palet de briquetas ECO para estufa de leña – 112 packs (1.085 kg) |
| lv-902501 | Palet de leña de olivo LEÑAS OLIVER – 2 m³ |
| lv-902500 | Palet de leña de roble LEÑAS LEGUA – 340 kg |
| lv-902499 | Palet de leña de encina BIOENERGY – 50 sacos x 12 kg |
| lv-902498 | Yumbo de leña de encina – 500 kg |
| lv-902497 | Yumbo de leña de encina – 1.000 kg |
| lv-902282 | Estufa de pellet Diva de 9kW de potencia en color blanco |
| lv-902280 | Estufa de pellet KRONE 7 kW negra |
| lv-902269 | Estufa de pellet canal reducida 8 eclipse 8 kw |
| lv-902264 | Estufa de pellet pasillo KRONE 4.9 kW blanco |
| lv-902262 | Estufa de pellet Eco Mensa Nero 6,4 kw negro |
| lv-902259 | Estufa de pellets de 7 kW en color blanco,≈110 m³. Alta eficiencia (~88,5 %), depósito~13 kg, |

## 7. Problèmes détectés nécessitant une intervention (non corrigés ici, hors périmètre code)

- Doublon de GTIN réel toujours présent dans la donnée source : `lv-31003` (70 sacs) et `lv-31006` (40 sacs) partagent `ref = 8437015545004` — même si plus envoyé comme GTIN, les deux `ref` doivent être corrigées séparément auprès du fournisseur.
- Classe énergétique : aucune classe n'a été ajoutée (`energy_efficiency_class`) — la seule donnée connue (KVS 9114-V avec échangeur) ne s'applique pas à la 9114 sans échangeur vendue ici ; ne rien publier tant que la fiche exacte du modèle vendu n'est pas lue.
- `google_product_category` : déjà mappé par catégorie (`estufas-de-pellets`/`cocinas-de-lena`→ Heaters, `calderas-de-lena`→ Heaters, `pellets-de-madera`/`lena`/`madera-densificada`/`a-granel`→ Household Fuel) ; **les barbacoas Weber (`lv-902590`, `lv-902589`)** sont actuellement classées `madera-densificada` → Household Fuel, alors qu'elles devraient avoir une catégorie barbecue dédiée. Non corrigé dans cette passe (changement de taxonomie produit à valider séparément).
- HTTPS/flux (`link`, `image_link`) : non ré-audité dans cette passe, déjà couvert par `merchant:validate`/`merchant:feed-test` existants (voir `01-AUDIT.md`/`02-RAPPORT-FINAL.md`).

## 8. Informations non confirmées (rappel)

- Tout GTIN/EAN du catalogue (228/228).
- Tout MPN fabricant (228/228).
- Les 119 marques listées en section 6.
- « LEÑAS OLIVER », « LEÑAS LEGUA », « BIOENERGY » : chaînes ressemblant à des marques, non confirmées, non publiées.
- Classe énergétique UE pour les poêles/chaudières.
- Catégorie Google dédiée pour les barbecues Weber.

## 9. Actions recommandées

1. Faire lire les emballages/plaques signalétiques des 119 produits de la section 6 et renseigner `products.brand` (ou ajouter un needle précis dans `config/merchant.php`) un par un, jamais en bloc.
2. Obtenir les vrais GTIN (photo d'emballage ou facture fournisseur) produit par produit, en particulier pour les palettes Naturpellet 70/40 sacs qui partagent aujourd'hui la même référence fournisseur.
3. Si une vraie promotion datée est décidée, suivre la procédure de la section 4 avant d'activer `MERCHANT_REFERENCE_PRICES_VERIFIED`.
4. Vérifier « LEÑAS OLIVER », « LEÑAS LEGUA », « BIOENERGY » auprès du fournisseur avant de les ajouter comme marques.
5. Revoir la catégorie Google Merchant des 2 barbecues Weber.
