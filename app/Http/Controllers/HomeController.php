<?php

namespace App\Http\Controllers;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartException;
use App\Domain\Catalog\Catalog;
use App\Domain\Merchant\GoogleProductMapper;
use App\Repositories\LojaProduct;
use App\Support\Money;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $pelletProducts = LojaProduct::query()
            ->applyFilters(['category' => 'pellets-de-madera'])
            ->paginate(16);

        $lenhaProducts = LojaProduct::query()
            ->applyFilters(['category' => 'lena'])
            ->paginate(12);

        $chefProducts = LojaProduct::query()
            ->applyFilters(['category' => 'cocinas-de-lena'])
            ->paginate(12);

        $compactadaProducts = LojaProduct::query()
            ->applyFilters(['category' => 'madera-densificada'])
            ->paginate(12);

        $caldeiraProducts = LojaProduct::query()
            ->applyFilters(['category' => 'calderas-de-lena'])
            ->paginate(4);

        $granelProducts = LojaProduct::query()
            ->applyFilters(['category' => 'a-granel'])
            ->paginate(4);

        $madeiraFogoProducts = LojaProduct::query()
            ->applyFilters(['category' => 'lena'])
            ->paginate(8);

        $estufasPelletsProducts = LojaProduct::query()
            ->applyFilters(['category' => 'estufas-de-pellets'])
            ->paginate(12);

        // Nuevos pellets importados (ids 31001-31099) — zona propia en la home.
        $nuevosPelletsProducts = app(Catalog::class)->all()
            ->filter(fn ($p) => $p['id'] >= 31001 && $p['id'] <= 31099)
            ->values();

        return view('home', compact(
            'pelletProducts',
            'lenhaProducts',
            'chefProducts',
            'compactadaProducts',
            'caldeiraProducts',
            'granelProducts',
            'madeiraFogoProducts',
            'estufasPelletsProducts',
            'nuevosPelletsProducts'
        ));
    }

    public function quickView($id)
    {
        $product = app(Catalog::class)->find($id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
            ], 404);
        }

        // Same title/price/availability as the product page and the feed.
        $offer = app(GoogleProductMapper::class)->map($product);
        $product['title'] = $offer->title;
        $product['price'] = number_format($offer->offerAmount, 2, '.', '');

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    public function loja()
    {
        $filters = $this->listingFilters(request('product_cat'));

        $lojaProducts = LojaProduct::query()->applyFilters($filters)->paginate(12);
        $currentFilters = $this->currentFilters($filters);

        return view('loja', compact('lojaProducts', 'filters', 'currentFilters'));
    }

    public function carrinho(Cart $cart)
    {
        $lines = $cart->lines();
        $totals = $cart->totals();

        // Suggestions for the empty-cart state, from the catalog (no stale copy).
        $newProducts = app(Catalog::class)->all()
            ->where('in_stock', true)
            ->take(4)
            ->map(function (array $product) {
                $product['price'] = Money::toFloat($product['price']);
                $product['old_price'] = $product['old_price'] !== '' ? Money::toFloat($product['old_price']) : null;

                return $product;
            });

        return view('carrinho', [
            'cart' => $lines,
            'totals' => $totals,
            'totalItems' => $totals['items'],
            'totalPrice' => $totals['total'],
            'formattedTotalPrice' => Money::format($totals['total']),
            'formattedSubtotal' => Money::format($totals['subtotal']),
            'isEmpty' => $lines === [],
            'hasUnavailableItems' => $cart->hasUnavailableItems(),
            'newProducts' => $newProducts,
        ]);
    }

    /**
     * Product page. The H1, price, availability, JSON-LD and the feed entry
     * are all built from the same mapped offer.
     */
    public function show($slug)
    {
        $catalog = app(Catalog::class);
        $product = $catalog->findBySlug((string) $slug);

        if (! $product) {
            abort(404);
        }

        $allProducts = $catalog->all();

        $relatedProducts = $allProducts
            ->where('category', $product['category'])
            ->where('id', '!=', $product['id'])
            ->take(4);

        $currentIndex = $allProducts->search(fn ($item) => $item['id'] === $product['id']);

        $prevProduct = $currentIndex > 0 ? $allProducts[$currentIndex - 1] : null;
        $nextProduct = ($currentIndex !== false && $currentIndex < $allProducts->count() - 1)
            ? $allProducts[$currentIndex + 1]
            : null;

        $formatPrice = fn ($price) => Money::format($price);

        $offer = app(GoogleProductMapper::class)->map($product);

        return view('products.show', compact(
            'product',
            'offer',
            'relatedProducts',
            'prevProduct',
            'nextProduct',
            'formatPrice'
        ));
    }

    public function category($category)
    {
        $filters = $this->listingFilters($category ?? request('product_cat'));

        $lojaProducts = LojaProduct::query()->applyFilters($filters)->paginate(12);
        $currentFilters = $this->currentFilters($filters);
        $categoryName = \App\Support\CategoryLabels::label($category);

        return view('category', compact('lojaProducts', 'filters', 'currentFilters', 'categoryName'));
    }

    public function addToCart(Request $request, Cart $cart): JsonResponse
    {
        if (! $request->input('product_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Falta el ID del producto.',
            ], 400);
        }

        try {
            $line = $cart->add($request->input('product_id'), (int) $request->input('quantity', 1));
        } catch (CartException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 422);
        }

        return response()->json($this->cartPayload($cart, [
            'message' => '¡Producto añadido al carrito!',
            'product' => $line,
        ]));
    }

    public function getCartContent(Cart $cart): JsonResponse
    {
        return response()->json($this->cartPayload($cart));
    }

    public function debugProducts()
    {
        abort_unless(config('app.debug'), 404);

        return response()->json(app(Catalog::class)->everything()->values());
    }

    public function updateCart(Request $request, Cart $cart): JsonResponse
    {
        if (! $cart->update($request->input('product_id'), (int) $request->input('quantity', 1))) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        }

        return response()->json($this->cartPayload($cart));
    }

    public function removeFromCart(Request $request, Cart $cart): JsonResponse
    {
        if (! $cart->remove($request->input('product_id'))) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        }

        return response()->json($this->cartPayload($cart, [
            'message' => 'Producto eliminado del carrito',
        ]));
    }

    public function clearCart(Cart $cart): JsonResponse
    {
        $cart->clear();

        return response()->json(['success' => true]);
    }

    public function getMiniCartHtml(Cart $cart): JsonResponse
    {
        $lines = $cart->lines();
        $totals = $cart->totals();

        return response()->json([
            'success' => true,
            'desktop_html' => view('partials.mini-cart', ['lines' => $lines, 'totals' => $totals, 'variant' => 'desktop'])->render(),
            'mobile_html' => view('partials.mini-cart', ['lines' => $lines, 'totals' => $totals, 'variant' => 'mobile'])->render(),
            'totalItems' => $totals['items'],
            'totalPrice' => $totals['total'],
            'formattedTotalPrice' => Money::format($totals['total']),
            'isEmpty' => $lines === [],
        ]);
    }

    public function listaDeDesejos()
    {
        return redirect()->route('wishlist.index', [], 301);
    }

    public function sobreNos()
    {
        return view('pages.sobre-nos');
    }

    public function avisosLegais()
    {
        return view('pages.avisos-legais');
    }

    public function contacto()
    {
        return view('pages.contacto');
    }

    public function politicaDePrivacidade()
    {
        return view('pages.politica-de-privacidade');
    }

    public function condicoesGeraisGeVendaCgv()
    {
        return view('pages.condicoes-gerais-de-venda-cgv');
    }

    public function termosCondicoesGeraisDeUtilizacaoTcg()
    {
        return view('pages.termos-e-condicoes-gerais-de-utilizacao-tcg');
    }

    public function politicaDeEntrega()
    {
        return view('pages.politica-de-entrega');
    }

    public function politicaDeReembolso()
    {
        return view('pages.politica-de-reembolso');
    }

    public function politicaDePagamento()
    {
        return view('pages.politica-de-pagamento');
    }

    public function certificaciones()
    {
        $certificaciones = \App\Support\Certifications::all();

        abort_if(empty($certificaciones), 404);

        return view('pages.certificaciones', [
            'certificaciones' => $certificaciones,
        ]);
    }

    /**
     * JSON contract used by the storefront JS (layouts/app.blade.php).
     * `totalPrice` is the amount the customer will pay: products plus
     * delivery when the delivery rate is confirmed, products only otherwise.
     */
    private function cartPayload(Cart $cart, array $extra = []): array
    {
        $totals = $cart->totals();

        return array_merge([
            'success' => true,
            'cart' => $cart->lines(),
            'totalItems' => $totals['items'],
            'subtotal' => number_format($totals['subtotal'], 2, '.', ''),
            'shipping' => $totals['shipping'] === null ? null : number_format($totals['shipping'], 2, '.', ''),
            'shippingLabel' => $totals['shipping_label'],
            'totalPrice' => number_format($totals['total'], 2, '.', ''),
            'formattedTotalPrice' => Money::format($totals['total']),
        ], $extra);
    }

    private function listingFilters(?string $category): array
    {
        // Default bounds come from the catalog itself, so no product is hidden
        // by a hard-coded price range.
        $prices = app(Catalog::class)->all()->map(fn ($p) => Money::toFloat($p['price']));
        $floor = $prices->isEmpty() ? 0 : floor($prices->min());
        $ceil = $prices->isEmpty() ? 0 : ceil($prices->max());

        return [
            'category' => $category,
            'min_price' => (float) request('min_price', $floor),
            'max_price' => (float) request('max_price', $ceil),
            'in_stock' => request('stock') == '0',
            'search' => request('s'),
            'orderby' => request('orderby', 'menu_order'),
            'page' => request('page', 1),
            'product_visibility' => request('product_visibility'),
            'stock' => request('stock'),
            'colors' => request('colors', []),
        ];
    }

    private function currentFilters(array $filters): array
    {
        return [
            'min_price' => $filters['min_price'],
            'max_price' => $filters['max_price'],
            'product_cat' => $filters['category'],
            'stock' => request('stock'),
            'orderby' => $filters['orderby'],
            's' => $filters['search'],
        ];
    }
}
