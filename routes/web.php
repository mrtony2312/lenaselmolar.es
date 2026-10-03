<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Merchant\GoogleFeedController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/',[App\Http\Controllers\HomeController::class,'index'])->name('home');


Route::get('/loja',[App\Http\Controllers\HomeController::class,'loja'])->name('loja');
Route::get('/carrinho',[App\Http\Controllers\HomeController::class,'carrinho'])->name('carrinho');
Route::get('/lista-de-desejos',[App\Http\Controllers\HomeController::class,'listaDeDesejos'])->name('lista-de-desejos');



Route::get('/producto/{slug}', [HomeController::class, 'show'])->name('product.show');
Route::get('/categoria/{category}', [HomeController::class, 'category'])->name('category');
//Route::get('/lista-desejos/adicionar/{productId}', [HomeController::class, 'addToWishlist'])->name('wishlist.add');



if (config('app.debug')) {
    Route::get('/debug/products', [HomeController::class, 'debugProducts']);
}

Route::get('/feed/google-merchant.xml', [FeedController::class, 'googleMerchant'])->name('feed.google-merchant');
Route::get('/feeds/google-shopping.xml', [GoogleFeedController::class, 'view'])->name('feed.google-shopping');
Route::get('/feeds/google-shopping/descargar.xml', [GoogleFeedController::class, 'download'])->name('feed.google-shopping.download');

Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// robots.txt is generated so the Sitemap line is an absolute URL on the
// configured domain (a relative "Sitemap: /sitemap.xml" is invalid).
Route::get('/robots.txt', function () {
    return response(view('robots', ['sitemap' => route('sitemap')])->render(), 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('robots');

Route::get('/{slug}', [App\Http\Controllers\LandingController::class, 'show'])
    ->where('slug', implode('|', array_keys(config('landing_pages', []))))
    ->name('landing');

Route::get('/sobre-nosotros',[App\Http\Controllers\HomeController::class,'sobreNos'])->name('sobre-nos');
Route::get('/avisos-legais',[App\Http\Controllers\HomeController::class,'avisosLegais'])->name('avisos-legais');
Route::get('/contacto',[App\Http\Controllers\HomeController::class,'contacto'])->name('contacto');
Route::get('/politica-de-privacidade',[App\Http\Controllers\HomeController::class,'politicaDePrivacidade'])->name('politica-de-privacidade');
Route::get('/condicoes-gerais-de-venda-cgv',[App\Http\Controllers\HomeController::class,'condicoesGeraisGeVendaCgv'])->name('condicoes-gerais-de-venda-cgv');
Route::get('/termos-e-condicoes-gerais-de-utilizacao-tcg',[App\Http\Controllers\HomeController::class,'termosCondicoesGeraisDeUtilizacaoTcg'])->name('termos-e-condicoes-gerais-de-utilizacao-tcg');
Route::get('/politica-de-entrega',[App\Http\Controllers\HomeController::class,'politicaDeEntrega'])->name('politicaDeEntrega');
Route::get('/politica-de-reembolso',[App\Http\Controllers\HomeController::class,'politicaDeReembolso'])->name('politicaDeReembolso');
Route::get('/politica-de-pagamento',[App\Http\Controllers\HomeController::class,'politicaDePagamento'])->name('politicaDePagamento');
Route::get('/certificaciones',[App\Http\Controllers\HomeController::class,'certificaciones'])->name('certificaciones');
Route::post('/contacto', [App\Http\Controllers\ContactController::class, 'send'])->middleware('throttle:5,1')->name('contacto.send');



Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');




Route::post('/carrinho/adicionar', [HomeController::class, 'addToCart'])->middleware('throttle:60,1')->name('cart.add');
Route::get('/carrinho/conteudo', [HomeController::class, 'getCartContent'])->name('cart.content');

Route::post('/carrinho/atualizar', [HomeController::class, 'updateCart'])->name('cart.update');
Route::post('/carrinho/remover', [HomeController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/carrinho/limpar', [HomeController::class, 'clearCart'])->name('cart.clear');

Route::get('/carrinho/mini-cart-html', [HomeController::class, 'getMiniCartHtml'])->name('cart.mini.html');




// Checkout routes
Route::get('/finalizacao-de-compra', [CheckoutController::class, 'show'])->name('checkout');
Route::get('/finalizacao-de-compra/confirmacao', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');



Route::prefix('lista-desejos')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/adicionar', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/remover', [WishlistController::class, 'remove'])->name('wishlist.remove');
  //  Route::get('/contador', [WishlistController::class, 'getCount'])->name('wishlist.count');
});


Route::get('/product/quick-view/{id}', [App\Http\Controllers\HomeController::class, 'quickView'])->name('product.quickview');


// Ajoutez cette ligne dans vos routes
Route::get('/refresh-csrf-token', function() {
    return response()->json([
        'token' => csrf_token()
    ]);
})->middleware('web')->name('refresh');

Route::get('/debug/session', function () {
    abort_unless(config('app.debug'), 404);
    echo '<h1>Session Debug</h1>';

    echo '<h2>Toutes les données de session :</h2>';
    echo '<pre>';
    print_r(session()->all());
    echo '</pre>';

    echo '<h2>Panier :</h2>';
    echo '<pre>';
    print_r(session('cart', []));
    echo '</pre>';

    echo '<h2>Infos session :</h2>';
    echo '<ul>';
    echo '<li>Session ID: ' . session()->getId() . '</li>';
    echo '<li>CSRF Token: ' . csrf_token() . '</li>';
    echo '<li>Durée de vie: ' . config('session.lifetime') . ' minutes</li>';
    echo '</ul>';

    echo '<h2>Cookies :</h2>';
    echo '<pre>';
    print_r($_COOKIE);
    echo '</pre>';
});
