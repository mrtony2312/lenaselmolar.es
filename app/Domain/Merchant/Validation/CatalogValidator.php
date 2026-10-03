<?php

namespace App\Domain\Merchant\Validation;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\ProductPresenter;
use App\Domain\Merchant\GoogleProductMapper;
use App\Domain\Merchant\Support\Availability;
use App\Domain\Merchant\Support\Gtin;
use App\Domain\Merchant\Support\ProductImage;
use App\DTO\Merchant\GoogleProductData;
use App\Support\Certifications;
use App\Support\ShippingPolicy;
use App\Support\UnitPricing;
use Illuminate\Support\Str;

/**
 * Checks every product and the site configuration against the Google
 * Merchant Center product data specification and policies that can be
 * verified from this codebase. Anything needing external evidence is
 * reported for human verification — never declared compliant.
 *
 * CRITICAL product issues block the product from the feed.
 */
class CatalogValidator
{
    private const ACCEPTED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/tiff'];

    private const PROMO_WORDS = [
        'oferta', 'ofertón', 'descuento', 'rebaja', 'rebajas', 'promoción', 'promo', 'liquidación',
        'gratis', 'envío gratis', 'envío gratuito', 'mejor precio', 'el más barato', 'barato', 'chollo', 'black friday',
    ];

    private const CERTIFICATION_WORDS = '/\b(din\s*\+?\s*plus|en\s*plus|enplus|certificad[oa]s?|certificaci[oó]n|iso\s*9001|nf\s+bois)\b/iu';

    /** Words that betray an untranslated French/Portuguese source. */
    private const FOREIGN_WORDS = '/\b(palette|sacs|bois|granul[ée]s|b[uû]ches|chauffage|po[eê]le|cuisini[eè]re|bouilleur|avec|qualit[ée]|lenha|madeira|palete|paletes|aquecimento|qualidade|caixas|toros|n[aã]o|com\s+caldeira|fog[aã]o)\b/iu';

    /** Image file name patterns of photos taken from marketplaces / other shops. */
    private const FOREIGN_SOURCE_PATTERNS = [
        '/_AC_SL\d+_/i' => 'marketplace (Amazon) image',
        '/^\d{2}[A-Za-z0-9+]{7,10}L(-\d+x\d+)?\.(jpe?g|webp|png)$/' => 'marketplace (Amazon) image id',
        '/^images(-\d+)*\.(jpe?g|png|webp)$/i' => 'generic web-search file name',
        '/wechatimage/i' => 'messaging-app image',
        '/large_default/i' => 'export from another shop (PrestaShop)',
        '/^[0-9a-f]{32}/i' => 'anonymous hashed file name',
    ];

    private ValidationReport $report;

    /** @var array<string, bool>|null */
    private ?array $taxonomy = null;

    public function __construct(
        private Catalog $catalog,
        private GoogleProductMapper $mapper,
    ) {}

    public function run(): ValidationReport
    {
        $this->report = new ValidationReport();

        $this->checkAccount();

        $products = $this->catalog->everything();
        $offers = [];

        foreach ($products as $row) {
            $dto = $this->mapper->map($row);
            $offers[$row['id']] = $dto;
            $this->report->addProduct($row['id'], $dto->sku, $dto->title);

            if (! $row['is_active']) {
                $this->report->exclude($row['id'], 'Producto desactivado (is_active = false): retirado de la venta.');
            }

            if ($row['merchant_excluded']) {
                $this->report->exclude($row['id'], 'Exclusión manual: '.($row['merchant_exclusion_reason'] ?: 'sin motivo indicado'));
            }

            $this->checkProduct($row, $dto);
        }

        $active = $products->filter(fn ($p) => $p['is_active']);
        $this->checkDuplicateIds($active, $offers);
        $this->checkDuplicateGtins($active, $offers);
        $this->checkDuplicateOffers($active, $offers);
        $this->checkSharedImages($active, $offers);

        return $this->report;
    }

    // ------------------------------------------------------------------
    // Account / site level
    // ------------------------------------------------------------------

    private function checkAccount(): void
    {
        $appUrl = (string) config('app.url');
        $host = (string) parse_url($appUrl, PHP_URL_HOST);

        if (! str_starts_with($appUrl, 'https://')) {
            $this->account(Severity::Critical, Issue::URL, 'APP_URL', $appUrl, 'https://<dominio-real>',
                'Todas las URL del flux (link, image_link) y el canonical se generan a partir de APP_URL: sin HTTPS, Google rechaza los artículos.',
                'Definir APP_URL=https://<dominio> en el .env de producción.');
        }

        if (in_array($host, ['localhost', '127.0.0.1', ''], true) || str_ends_with($host, '.test') || str_ends_with($host, '.local')) {
            $this->account(Severity::Critical, Issue::URL, 'APP_URL host', $host ?: '(vacío)', 'dominio público verificado en Merchant Center',
                'Google no puede rastrear un host local; el dominio del flux debe ser el reclamado en Merchant Center.',
                'Definir APP_URL con el dominio público y reclamar/verificar ese dominio en Merchant Center.');
        }

        $canonicalHost = $this->htaccessCanonicalHost();
        if ($canonicalHost && $host && strcasecmp($canonicalHost, $host) !== 0) {
            $this->account(Severity::Critical, Issue::URL, '.htaccess canonical host', $canonicalHost, 'igual al host de APP_URL ('.$host.')',
                'El servidor redirige (301) cualquier otro host hacia '.$canonicalHost.': los link del flux apuntarían a una URL que redirige a otro dominio.',
                'Alinear APP_URL y la regla de host canónico de .htaccess con el dominio reclamado en Merchant Center.');
        }

        if ($this->catalog->source() !== Catalog::SOURCE_DATABASE) {
            $this->account(Severity::Critical, Issue::CONSISTENCY, 'catalog source', $this->catalog->source(), 'database',
                'El catálogo se está leyendo del antiguo config/loja_products.php (importación histórica), no de la base de datos.',
                'Ejecutar las migraciones y la importación (php artisan migrate && php artisan db:seed --class=CatalogSeeder).');
        }

        if (! ShippingPolicy::confirmed()) {
            $this->account(Severity::Critical, Issue::SHIPPING, 'merchant.shipping',
                sprintf('publish=%s, coverage_confirmed=%s', var_export((bool) config('merchant.shipping.publish'), true), var_export((bool) config('merchant.shipping.coverage_confirmed'), true)),
                'tarifa y cobertura confirmadas por la empresa',
                'El coste y la cobertura de entrega no están confirmados: el sitio indica "a confirmar" y el flux no envía g:shipping. Google exige costes de envío exactos (flux o configuración de cuenta) y que todos los costes se conozcan antes del pago.',
                'Fijar la tarifa real (MERCHANT_SHIPPING_PRICE, plazos) y la cobertura real; si no se entrega en toda España (Baleares, Canarias, Ceuta, Melilla), configurar regiones en Merchant Center. Después: MERCHANT_SHIPPING_PUBLISH=true y MERCHANT_SHIPPING_COVERAGE_CONFIRMED=true.');
        } elseif (! ShippingPolicy::timesPublished()) {
            $this->account(Severity::Important, Issue::SHIPPING, 'merchant.shipping.publish_transit', 'false', 'plazos confirmados',
                'Sin plazos de preparación/transporte publicados, Google estima los plazos.',
                'Confirmar los plazos reales y activar MERCHANT_SHIPPING_PUBLISH_TRANSIT.');
        }

        if (trim((string) config('bank.iban')) === '') {
            $this->account(Severity::Critical, Issue::CHECKOUT, 'BANK_IBAN', '(vacío)', 'IBAN real del titular '.config('bank.holder'),
                'La transferencia es el único medio de pago y ni la confirmación ni el e-mail muestran datos bancarios: el cliente no puede pagar.',
                'Definir BANK_IBAN (y BANK_BIC, BANK_NAME) en el .env de producción.');
        }

        if (trim((string) config('mail.admin_email')) === '') {
            $this->account(Severity::Important, Issue::CHECKOUT, 'MAIL_ADMIN_EMAIL', '(vacío)', 'buzón que recibe los pedidos',
                'La tienda no recibiría la notificación de los pedidos.',
                'Definir MAIL_ADMIN_EMAIL.');
        }

        if (trim((string) config('merchant.nap.email')) === '') {
            $this->account(Severity::Warning, Issue::BUSINESS, 'merchant.nap.email', '(vacío)', 'e-mail de atención al cliente publicado',
                'Solo se publican teléfono, WhatsApp y formulario. Los pedidos se envían desde '.config('mail.from.address').', que no aparece en el sitio.',
                'Confirmar el e-mail de atención al cliente y añadirlo en merchant.nap.email (se mostrará en footer, aviso legal y JSON-LD).');
        }

        if (config('merchant.reference_prices_verified')) {
            $this->account(Severity::Warning, Issue::PRICE, 'MERCHANT_REFERENCE_PRICES_VERIFIED', 'true', 'historial de precios documentado',
                'Los precios tachados se publican: cada old_price debe ser el precio más bajo aplicado en los 30 días anteriores.',
                'Conservar la prueba documental de cada precio de referencia.');
        }

        if (! is_file((string) config('merchant.taxonomy_file'))) {
            $this->account(Severity::Warning, Issue::CATEGORY, 'merchant.taxonomy_file', '(no encontrado)', 'taxonomía oficial de Google',
                'No se pueden verificar los ID de google_product_category.',
                'Descargar https://www.google.com/basepages/producttype/taxonomy-with-ids.es-ES.txt en resources/merchant/.');
        }
    }

    private function htaccessCanonicalHost(): ?string
    {
        $file = base_path('.htaccess');

        if (! is_file($file)) {
            return null;
        }

        if (preg_match('/RewriteCond\s+%\{HTTP_HOST\}\s+!\^([A-Za-z0-9.\\\\-]+)\$/', (string) file_get_contents($file), $m)) {
            return str_replace('\\', '', $m[1]);
        }

        return null;
    }

    // ------------------------------------------------------------------
    // Product level
    // ------------------------------------------------------------------

    private function checkProduct(array $row, GoogleProductData $dto): void
    {
        $this->checkCore($row, $dto);
        $this->checkPrice($row, $dto);
        $this->checkAvailability($row, $dto);
        $this->checkTitle($row, $dto);
        $this->checkDescription($row, $dto);
        $this->checkImages($row, $dto);
        $this->checkIdentifiers($row, $dto);
        $this->checkCategory($row, $dto);
        $this->checkUnitPricing($row, $dto);
        $this->checkEnergyLabel($row, $dto);
        $this->checkSchema($dto);
    }

    private function checkCore(array $row, GoogleProductData $dto): void
    {
        if ($dto->link === '') {
            $this->product($row, $dto, Severity::Critical, Issue::URL, 'link', '(vacío)', 'URL HTTPS de la ficha',
                'Sin slug no hay página de destino.', 'Asignar un slug único al producto.');
        } elseif (! str_starts_with($dto->link, 'https://')) {
            $this->product($row, $dto, Severity::Critical, Issue::URL, 'link', $dto->link, 'https://…',
                'Google exige páginas de destino accesibles por HTTPS.', 'Corregir APP_URL (ver incidencia de cuenta).');
        }

        if ($row['slug'] !== '' && ! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $row['slug'])) {
            $this->product($row, $dto, Severity::Warning, Issue::URL, 'slug', $row['slug'], 'minúsculas, cifras y guiones',
                'Una URL con caracteres especiales puede codificarse de forma distinta entre el flux y el sitio.', 'Normalizar el slug y añadir una redirección 301 desde el antiguo.');
        }
    }

    private function checkPrice(array $row, GoogleProductData $dto): void
    {
        if ($dto->offerAmount <= 0) {
            $this->product($row, $dto, Severity::Critical, Issue::PRICE, 'price', $row['price'], '> 0',
                'Precio ausente o nulo: el producto no se puede comprar a ese precio.', 'Introducir el precio real IVA incluido.');
        }

        if (! preg_match('/^\d+\.\d{2} [A-Z]{3}$/', $dto->price)) {
            $this->product($row, $dto, Severity::Critical, Issue::PRICE, 'g:price', $dto->price, '"123.45 EUR"',
                'Formato de precio no conforme a la especificación.', 'Revisar el valor de products.price.');
        }

        if ($dto->offerAmount > 0 && $dto->offerAmount < 1) {
            $this->product($row, $dto, Severity::Important, Issue::PRICE, 'price', $row['price'], 'precio real del producto',
                'Precio sospechosamente bajo (¿error de separador decimal?).', 'Verificar el precio.');
        }
    }

    private function checkAvailability(array $row, GoogleProductData $dto): void
    {
        $value = $dto->availability->value;

        if (! in_array($value, array_column(Availability::cases(), 'value'), true)) {
            $this->product($row, $dto, Severity::Critical, Issue::AVAILABILITY, 'g:availability', $value, 'in_stock | out_of_stock | preorder | backorder',
                'Valor de disponibilidad no admitido por Google.', 'Corregir el mapeo de disponibilidad.');
        }

        if ($dto->inStock !== (bool) $row['in_stock']) {
            $this->product($row, $dto, Severity::Critical, Issue::AVAILABILITY, 'in_stock', var_export($row['in_stock'], true), $value,
                'La disponibilidad enviada no coincide con la base de datos.', 'Revisar el mapper.');
        }
    }

    private function checkTitle(array $row, GoogleProductData $dto): void
    {
        $title = $dto->title;

        if ($title === '') {
            $this->product($row, $dto, Severity::Critical, Issue::TITLE, 'title', '(vacío)', 'título descriptivo', 'Título obligatorio.', 'Introducir el título.');

            return;
        }

        if (mb_strlen((string) $row['title']) > 150) {
            $this->product($row, $dto, Severity::Important, Issue::TITLE, 'title', mb_strlen((string) $row['title']).' caracteres', '≤ 150',
                'Google trunca/rechaza títulos de más de 150 caracteres; el flux lo corta con "…".', 'Acortar el título conservando lo esencial.');
        }

        if (preg_match('/\d+(?:[.,]\d+)?\s?(€|eur\b|euros?)/iu', $title)) {
            $this->product($row, $dto, Severity::Important, Issue::TITLE, 'title', $title, 'sin precio',
                'Un precio en el título queda desfasado y está prohibido por las reglas editoriales.', 'Quitar el precio del título.');
        }

        foreach (self::PROMO_WORDS as $word) {
            if (preg_match('/\b'.preg_quote($word, '/').'\b/iu', $title)) {
                $this->product($row, $dto, Severity::Important, Issue::TITLE, 'title', $title, 'sin texto promocional',
                    'Texto promocional ("'.$word.'") en el título: no permitido por las reglas editoriales.', 'Quitar "'.$word.'" del título.');
                break;
            }
        }

        if (preg_match_all('/\b[A-ZÁÉÍÓÚÑ]{4,}\b/u', $title, $caps) && count($caps[0]) >= 2) {
            $this->product($row, $dto, Severity::Warning, Issue::TITLE, 'title', $title, 'sin mayúsculas abusivas',
                'Palabras enteras en mayúsculas ('.implode(', ', $caps[0]).'): las reglas editoriales lo limitan a siglas y marcas.', 'Escribir en minúsculas salvo marcas/siglas.');
        }

        if (preg_match('/\(ref\.?\s*\d+\)/iu', $title)) {
            $this->product($row, $dto, Severity::Warning, Issue::TITLE, 'title', $title, 'sin código interno',
                'Código interno en el título (probablemente para distinguir un duplicado).', 'Diferenciar el título por una característica real o resolver el duplicado.');
        }

        if (preg_match(self::CERTIFICATION_WORDS, $title, $m) && Certifications::all() === []) {
            $this->product($row, $dto, Severity::Important, Issue::POLICY, 'title', $title, 'certificación documentada',
                'El título menciona "'.$m[0].'" y no hay ningún certificado documentado (número + validez) para este producto.',
                'Obtener el certificado del fabricante (n.º ENplus/DINplus verificable) o quitar la mención.');
        }

        if (preg_match(self::FOREIGN_WORDS, $title, $m)) {
            $this->product($row, $dto, Severity::Important, Issue::LANGUAGE, 'title', $title, 'español (es)',
                'Palabra no española en el título ("'.$m[0].'").', 'Traducir el título al español.');
        }
    }

    private function checkDescription(array $row, GoogleProductData $dto): void
    {
        $text = $dto->description;

        if ($text === '') {
            $this->product($row, $dto, Severity::Critical, Issue::DESCRIPTION, 'description', '(vacío)', 'descripción del producto',
                'Descripción obligatoria.', 'Redactar una descripción fiel del producto.');

            return;
        }

        if (mb_strlen($text) < 40) {
            $this->product($row, $dto, Severity::Warning, Issue::DESCRIPTION, 'description', $text, '≥ 40 caracteres con características reales',
                'Descripción demasiado corta para describir el producto.', 'Completar con datos verificados (formato, cantidad, dimensiones).');
        }

        if (preg_match(self::FOREIGN_WORDS, $text, $m)) {
            $this->product($row, $dto, Severity::Important, Issue::LANGUAGE, 'description', Str::limit($text, 80), 'español (es)',
                'Palabra no española en la descripción ("'.$m[0].'"): resto de una fuente francesa/portuguesa.', 'Traducir la descripción.');
        }

        if (preg_match(self::CERTIFICATION_WORDS, $text, $m) && Certifications::all() === []) {
            $this->product($row, $dto, Severity::Important, Issue::POLICY, 'description', '…'.$m[0].'…', 'certificación documentada',
                'La descripción menciona "'.$m[0].'" sin certificado documentado.', 'Aportar el certificado verificable o quitar la mención.');
        }

        if (preg_match('/\b(100\s*%\s*(natural|seco|garantizado)|el mejor|n[º°]\s*1|calidad insuperable)\b/iu', $text, $m)) {
            $this->product($row, $dto, Severity::Warning, Issue::POLICY, 'description', '…'.$m[0].'…', 'afirmación verificable',
                'Afirmación absoluta difícil de demostrar ("'.$m[0].'").', 'Sustituir por una característica medible y documentada.');
        }
    }

    private function checkImages(array $row, GoogleProductData $dto): void
    {
        $gallery = ProductImage::galleryPaths($row);

        if ($dto->imageLink === '' || $gallery === []) {
            $this->product($row, $dto, Severity::Critical, Issue::IMAGE, 'image_link', '(vacío)', 'foto real del producto',
                'image_link es obligatorio.', 'Añadir una foto del producto real (≥ 500×500 px).');

            return;
        }

        $mainPath = ltrim((string) parse_url($dto->imageLink, PHP_URL_PATH), '/');
        $mainSize = ProductImage::dimensions(rawurldecode($mainPath));
        $min = (int) config('merchant.image_min_dimension', 500);

        if ($mainSize === null) {
            $this->product($row, $dto, Severity::Critical, Issue::IMAGE, 'image_link', $mainPath, 'HTTP 200',
                'La imagen principal no existe en el servidor (404).', 'Subir el archivo o corregir la ruta.');
        } else {
            if (! in_array($mainSize[2], self::ACCEPTED_IMAGE_MIMES, true)) {
                $this->product($row, $dto, Severity::Critical, Issue::IMAGE, 'image_link', $mainSize[2], 'JPEG, PNG, GIF, WebP, BMP o TIFF',
                    'Formato de imagen no admitido.', 'Convertir la imagen.');
            }

            if ($mainSize[0] < 100 || $mainSize[1] < 100) {
                $this->product($row, $dto, Severity::Critical, Issue::IMAGE, 'image_link', $mainSize[0].'×'.$mainSize[1], '≥ 100×100 (mínimo Google)',
                    'Imagen principal por debajo del mínimo absoluto de Google.', 'Sustituir por una foto de mayor resolución.');
            } elseif ($mainSize[0] < $min || $mainSize[1] < $min) {
                $this->product($row, $dto, Severity::Warning, Issue::IMAGE, 'image_link', $mainSize[0].'×'.$mainSize[1], '≥ '.$min.'×'.$min,
                    'Resolución inferior a la recomendada.', 'Sustituir por una foto ≥ '.$min.' px del producto real.');
            }
        }

        $titleSlug = Str::slug($dto->title.' '.$row['title']);
        $brand = $dto->brand;

        foreach ($gallery as $path) {
            $file = basename($path);
            $fileSlug = Str::slug(pathinfo($file, PATHINFO_FILENAME));

            if (ProductImage::dimensions($path) === null) {
                $this->product($row, $dto, Severity::Important, Issue::IMAGE, 'images', $path, 'archivo existente',
                    'Imagen de la galería no encontrada (404 en la ficha).', 'Subir el archivo o quitarlo de la galería.');
            }

            foreach ($this->knownBrandTokens() as $token => $label) {
                if (str_contains('-'.$fileSlug.'-', '-'.$token.'-') && ! str_contains('-'.$titleSlug.'-', '-'.$token.'-')) {
                    $this->product($row, $dto, $brand ? Severity::Critical : Severity::Important, Issue::IMAGE, 'images', $file,
                        'foto de este producto ('.($brand ?: 'marca del título').')',
                        'El nombre del archivo indica otra marca ("'.$label.'") que no aparece en el título: la foto puede mostrar otro producto (representación engañosa).',
                        $brand ? 'Quitar esta imagen de la galería y usar una foto del producto vendido.' : 'Verificar si el título traduce mal la marca "'.$label.'" o si la foto es de otro producto.');
                }
            }

            foreach (['kw' => '/(\d+(?:[.,]\d+)?)\s*-?\s*kw\b/i', 'cm' => '/(\d+)\s*-?\s*cm\b/i'] as $unit => $pattern) {
                $fileValues = $this->numbers($pattern, str_replace('-', ' ', pathinfo($file, PATHINFO_FILENAME)));
                $titleValues = $this->numbers($pattern, $row['title']);

                // File names lose the decimal separator ("5,5 kW" → "55-kw"):
                // compare digit strings only.
                $digits = fn (array $values) => array_map(fn ($v) => str_replace('.', '', $v), $values);

                if ($fileValues && $titleValues && array_diff($digits($fileValues), $digits($titleValues))) {
                    $this->product($row, $dto, Severity::Important, Issue::IMAGE, 'images', $file,
                        'foto con '.implode('/', $titleValues).' '.$unit,
                        'La imagen corresponde a '.implode('/', $fileValues).' '.$unit.' y el producto vendido a '.implode('/', $titleValues).' '.$unit.'.',
                        'Usar la foto del modelo/formato exacto.');
                }
            }

            foreach (self::FOREIGN_SOURCE_PATTERNS as $pattern => $label) {
                if (preg_match($pattern, $file)) {
                    $this->product($row, $dto, Severity::Warning, Issue::IMAGE, 'images', $file, 'foto propia o del fabricante con derechos de uso',
                        'Origen de la imagen dudoso ('.$label.'): verificar los derechos y que muestre exactamente este producto.',
                        'Sustituir por una foto propia o del fabricante.');
                    break;
                }
            }
        }
    }

    private function checkIdentifiers(array $row, GoogleProductData $dto): void
    {
        $ref = trim($row['ref']);
        $digits = preg_replace('/\D+/', '', $ref) ?? '';

        if ($dto->gtin && $dto->gtinSource === 'ref') {
            $this->product($row, $dto, Severity::Warning, Issue::IDENTIFIER, 'gtin', $dto->gtin.' (de ref)', 'GTIN confirmado en products.gtin',
                'GTIN tomado del campo histórico ref (código del proveedor). Debe ser el código de barras del artículo vendido; si es el del saco y se vende un palé, hay que declarar el multipack o el GTIN del palé.',
                'Comprobar en el embalaje/ficha del fabricante y copiarlo en products.gtin.');
        }

        if ($dto->gtin === null && preg_match('/^\d{8,14}$/', $ref) && ! str_contains($ref, (string) $row['id'])) {
            $this->product($row, $dto, Severity::Important, Issue::IDENTIFIER, 'ref', $ref, 'GTIN válido (GS1) o vacío',
                str_starts_with($digits, '2') ? 'Código interno de tienda (prefijo GS1 2xx): no es un GTIN del fabricante; no se envía.' : 'Código de barras con dígito de control inválido: no se envía.',
                'Obtener el GTIN real del fabricante o dejarlo vacío.');
        }

        if (! empty($row['gtin']) && ! Gtin::isValid((string) $row['gtin'])) {
            $this->product($row, $dto, Severity::Critical, Issue::IDENTIFIER, 'products.gtin', (string) $row['gtin'], 'GTIN-8/12/13/14 válido',
                'GTIN introducido inválido.', 'Corregir el GTIN con el del embalaje.');
        }

        if ($dto->brand !== null && $dto->brandSource === 'inferred') {
            $this->product($row, $dto, Severity::Warning, Issue::IDENTIFIER, 'brand', $dto->brand.' (deducida del título)', 'marca confirmada en products.brand',
                'La marca se deduce del título; puede ser un nombre comercial o de modelo.', 'Confirmar el fabricante y guardarlo en products.brand.');
        }

        if ($dto->brand !== null) {
            foreach ([config('merchant.nap.legal_name'), config('merchant.nap.commercial_name'), config('app.name')] as $store) {
                if ($store && strcasecmp(trim($dto->brand), trim((string) $store)) === 0) {
                    $this->product($row, $dto, Severity::Critical, Issue::IDENTIFIER, 'brand', $dto->brand, 'fabricante real',
                        'El nombre de la tienda no es la marca de un producto de terceros.', 'Indicar la marca del fabricante.');
                }
            }
        }

        if ($dto->sendIdentifierExists) {
            $this->product($row, $dto, Severity::Warning, Issue::IDENTIFIER, 'identifier_exists', 'no', 'GTIN real si el fabricante lo asigna',
                'Se envía identifier_exists=no (sin GTIN ni MPN). Correcto solo si el fabricante no asigna GTIN (p. ej. leña a granel); para sacos de pellets y aparatos de marca suele existir.',
                'Si el producto tiene código de barras, guardarlo en products.gtin.');
        }
    }

    private function checkCategory(array $row, GoogleProductData $dto): void
    {
        $gpc = $dto->googleProductCategory;

        if ($gpc === null) {
            $this->product($row, $dto, Severity::Warning, Issue::CATEGORY, 'google_product_category', '(vacío)', 'ID de la taxonomía Google',
                'Google asignará una categoría automáticamente.', 'Añadir la categoría de la tienda en merchant.google_product_category.');
        } elseif (ctype_digit($gpc) && ($taxonomy = $this->taxonomy()) !== [] && ! isset($taxonomy[$gpc])) {
            $this->product($row, $dto, Severity::Important, Issue::CATEGORY, 'google_product_category', $gpc, 'ID existente en la taxonomía',
                'ID de categoría inexistente en la taxonomía oficial.', 'Elegir un ID válido.');
        }

        $title = UnitPricing::normalize($row['title']);
        $category = $row['category'];

        if (str_contains($title, 'madera densificada') && $category !== 'madera-densificada') {
            $this->product($row, $dto, Severity::Important, Issue::CATEGORY, 'category', $category, 'madera-densificada',
                'El título describe madera densificada pero el producto está en "'.$category.'" (la ficha, el menú y product_type muestran otra categoría).',
                'Mover el producto a la categoría madera-densificada.');
        }

        if (str_contains($title, 'pellet') && ! str_contains($title, 'estufa') && in_array($category, ['lena', 'madera-densificada', 'cocinas-de-lena', 'calderas-de-lena'], true)) {
            $this->product($row, $dto, Severity::Important, Issue::CATEGORY, 'category', $category, 'pellets-de-madera',
                'Pellets clasificados en una categoría de leña/aparatos.', 'Corregir la categoría.');
        }
    }

    /**
     * Far outside UnitPricing::PLAUSIBLE_RANGES, the quantity in the title
     * and the price cannot both be right: the offer shown to Google would be
     * misleading, so the product is blocked.
     */
    private function checkUnitPricing(array $row, GoogleProductData $dto): void
    {
        $parsed = UnitPricing::parseFromTitle($row['title'], $row['category']);
        $value = isset($row['unit_measure_value']) && $row['unit_measure_value'] !== null ? (float) $row['unit_measure_value'] : ($parsed['value'] ?? null);
        $unit = $row['unit_measure_unit'] ?? ($parsed['unit'] ?? null);

        if ($value && $unit && $dto->offerAmount > 0 && ! UnitPricing::isPlausible($dto->offerAmount, $value, $unit)) {
            [$low, $high] = UnitPricing::PLAUSIBLE_RANGES[$unit];
            $perUnit = $dto->offerAmount / $value;

            $this->product($row, $dto, Severity::Critical, Issue::PRICE, 'price / cantidad',
                number_format($dto->offerAmount, 2, ',', '.').' € para '.$value.' '.$unit.' = '.number_format($perUnit, 2, ',', '.').' €/'.$unit,
                'entre '.$low.' y '.$high.' €/'.$unit,
                'La cantidad del título y el precio son incompatibles: el cliente (y Google) recibirían una oferta engañosa.',
                'Corregir el título (cantidad real vendida) o el precio.');
        }

        if ($dto->unitPricingMeasure !== null) {
            return;
        }

        if ($parsed !== null) {
            $this->product($row, $dto, Severity::Important, Issue::PRICE, 'unit_pricing_measure', '(vacío)', $parsed['value'].' '.$parsed['unit'].' (según el título)',
                'Producto vendido por peso/volumen sin precio por unidad de medida (obligatorio en España, RD 3423/2000; recomendado por Google en la UE).',
                'Revisar y aplicar: php artisan products:backfill-unit-measure (deriva la cantidad del título).');
        }
    }

    private function checkEnergyLabel(array $row, GoogleProductData $dto): void
    {
        if (! in_array($row['category'], ['estufas-de-pellets', 'cocinas-de-lena', 'calderas-de-lena'], true)) {
            return;
        }

        if ($dto->certificationCode === null) {
            $this->product($row, $dto, Severity::Warning, Issue::POLICY, 'certification (EPREL)', '(vacío)', 'código EPREL del modelo',
                'Aparato de calefacción sujeto a etiqueta energética UE (Reglamentos 2015/1186 y 2015/1187): la etiqueta y la ficha de información deben mostrarse en la ficha, y Google puede exigir g:certification (EPREL) en la UE.',
                'Obtener el n.º EPREL y la etiqueta del fabricante: php artisan products:set-eprel {id} {code}; mostrar la etiqueta en la ficha.');
        }
    }

    private function checkSchema(GoogleProductData $dto): void
    {
        $ld = $dto->toJsonLd();

        if (($ld['offers']['price'] ?? null) !== number_format($dto->offerAmount, 2, '.', '')
            || ($ld['offers']['availability'] ?? null) !== $dto->availability->schemaUrl()
            || ($ld['name'] ?? null) !== $dto->title
            || ($ld['sku'] ?? null) !== $dto->id) {
            $this->report->add(new Issue(Severity::Critical, Issue::SCHEMA, 'JSON-LD', 'distinto del flux', 'idéntico al flux',
                'El JSON-LD no reproduce el precio/disponibilidad/título/SKU del flux.', 'Revisar GoogleProductData::toJsonLd().',
                $dto->productId, $dto->sku, $dto->title));
        }

        if (isset($ld['offers']['shippingDetails']) && ! ShippingPolicy::confirmed()) {
            $this->report->add(new Issue(Severity::Critical, Issue::SCHEMA, 'shippingDetails', 'publicado', 'ausente mientras la tarifa no esté confirmada',
                'El JSON-LD publica una tarifa de envío no confirmada.', 'Revisar GoogleProductData::toJsonLd().',
                $dto->productId, $dto->sku, $dto->title));
        }
    }

    // ------------------------------------------------------------------
    // Cross-product checks
    // ------------------------------------------------------------------

    private function checkDuplicateIds($products, array $offers): void
    {
        $seen = [];

        foreach ($products as $row) {
            $id = $offers[$row['id']]->id;

            if (isset($seen[$id])) {
                $this->product($row, $offers[$row['id']], Severity::Critical, Issue::IDENTIFIER, 'g:id', $id, 'único',
                    'ID de oferta duplicado (también #'.$seen[$id].').', 'Corregir el ID del producto.');
            }

            $seen[$id] = $row['id'];
        }

        $slugs = $products->groupBy('slug')->filter(fn ($g) => $g->count() > 1);

        foreach ($slugs as $slug => $group) {
            foreach ($group as $row) {
                $this->product($row, $offers[$row['id']], Severity::Critical, Issue::URL, 'slug', $slug, 'único',
                    'Varias fichas comparten la misma URL.', 'Dar un slug único a cada producto.');
            }
        }
    }

    private function checkDuplicateGtins($products, array $offers): void
    {
        $byGtin = [];

        foreach ($products as $row) {
            if ($gtin = $offers[$row['id']]->gtin) {
                $byGtin[$gtin][] = $row['id'];
            }
        }

        foreach ($byGtin as $gtin => $ids) {
            if (count($ids) < 2) {
                continue;
            }

            foreach ($ids as $id) {
                $row = $products->firstWhere('id', $id);
                $this->product($row, $offers[$id], Severity::Critical, Issue::IDENTIFIER, 'gtin', (string) $gtin, 'un GTIN por artículo',
                    'El mismo GTIN está en varios productos con cantidades/precios distintos (#'.implode(', #', $ids).'): Google no puede saber a qué artículo corresponde.',
                    'Verificar qué artículo lleva este código. Si es el del saco individual vendido en palés, dejar products.gtin vacío y confirmar el tratamiento multipack.');
            }
        }
    }

    private function checkDuplicateOffers($products, array $offers): void
    {
        $groups = [];

        foreach ($products as $row) {
            $dto = $offers[$row['id']];
            $parsed = UnitPricing::parseFromTitle($row['title'], $row['category']);

            $key = $dto->brand && $parsed
                ? 'b:'.Str::slug($dto->brand).'|'.$row['category'].'|'.$parsed['value'].$parsed['unit']
                : 't:'.Str::slug(preg_replace('/\(ref\.?[^)]*\)/iu', '', $row['title']) ?? $row['title']);

            $groups[$key][] = $row['id'];
        }

        foreach ($groups as $ids) {
            if (count($ids) < 2) {
                continue;
            }

            $prices = [];
            foreach ($ids as $id) {
                $prices[$id] = $offers[$id]->offerAmount;
            }

            $differentPrices = count(array_unique(array_map(fn ($p) => number_format($p, 2), $prices))) > 1;

            foreach ($ids as $id) {
                $row = $products->firstWhere('id', $id);
                $list = implode(', ', array_map(fn ($pid, $p) => '#'.$pid.' '.number_format($p, 2, ',', '.').' €', array_keys($prices), $prices));

                $this->product($row, $offers[$id],
                    $differentPrices ? Severity::Critical : Severity::Important,
                    Issue::PRICE, 'price', $list, 'un único precio por artículo idéntico',
                    $differentPrices
                        ? 'El mismo artículo (misma marca y cantidad) se vende a precios distintos: contradicción de precio para el cliente y para Google.'
                        : 'Ficha duplicada del mismo artículo al mismo precio.',
                    'Si son el mismo artículo, desactivar uno (is_active=false) y redirigir su URL; si son distintos, diferenciar el título con la característica real que los distingue.');
            }
        }
    }

    private function checkSharedImages($products, array $offers): void
    {
        $byFile = [];

        foreach ($products as $row) {
            foreach (ProductImage::galleryPaths($row) as $path) {
                // Same photo = same bytes of the full-size file (thumbnails
                // resolved to their original first).
                $original = ProductImage::largestVariant($path);
                $file = public_path($original);
                $key = is_file($file) ? md5_file($file) : 'missing:'.$original;
                $byFile[$key]['ids'][$row['id']] = true;
                $byFile[$key]['name'] = basename($original);
            }
        }

        foreach ($byFile as $group) {
            $file = $group['name'];
            $ids = array_keys($group['ids']);

            if (count($ids) < 2) {
                continue;
            }

            $brands = array_unique(array_filter(array_map(fn ($id) => $offers[$id]->brand, $ids)));

            foreach ($ids as $id) {
                $row = $products->firstWhere('id', $id);
                $this->product($row, $offers[$id], count($brands) > 1 ? Severity::Critical : Severity::Important, Issue::IMAGE, 'images', $file,
                    'foto propia de cada producto',
                    'La misma foto se usa en productos distintos (#'.implode(', #', $ids).')'.(count($brands) > 1 ? ' de marcas diferentes ('.implode(', ', $brands).')' : '').': al menos uno no muestra el artículo vendido.',
                    'Fotografiar o pedir al fabricante la imagen exacta de cada producto.');
            }
        }
    }

    // ------------------------------------------------------------------

    /**
     * @return array<string, string> slug token => display label
     */
    private function knownBrandTokens(): array
    {
        static $tokens = null;

        if ($tokens !== null) {
            return $tokens;
        }

        $tokens = [];
        $names = array_merge(array_values(config('merchant.brands', [])), config('merchant.image_brand_tokens', []));

        foreach ($names as $name) {
            $slug = Str::slug((string) $name);

            if (mb_strlen($slug) >= 4) {
                $tokens[$slug] = (string) $name;
            }
        }

        return $tokens;
    }

    /**
     * @return list<string>
     */
    private function numbers(string $pattern, string $text): array
    {
        if (! preg_match_all($pattern, $text, $m)) {
            return [];
        }

        return array_values(array_unique(array_map(fn ($v) => str_replace(',', '.', $v), $m[1])));
    }

    /**
     * @return array<string, bool>
     */
    private function taxonomy(): array
    {
        if ($this->taxonomy !== null) {
            return $this->taxonomy;
        }

        $this->taxonomy = [];
        $file = (string) config('merchant.taxonomy_file');

        if (is_file($file)) {
            foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
                if (preg_match('/^(\d+) - /', $line, $m)) {
                    $this->taxonomy[$m[1]] = true;
                }
            }
        }

        return $this->taxonomy;
    }

    private function account(Severity $severity, string $group, string $field, string $current, string $expected, string $reason, string $fix): void
    {
        $this->report->add(new Issue($severity, $group, $field, $current, $expected, $reason, $fix));
    }

    private function product(array $row, GoogleProductData $dto, Severity $severity, string $group, string $field, string $current, string $expected, string $reason, string $fix): void
    {
        $this->report->add(new Issue($severity, $group, $field, $current, $expected, $reason, $fix, $row['id'], $dto->sku, $dto->title));
    }
}
