<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HandleExpiredSession
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier uniquement pour la route checkout.store
        if (Route::currentRouteName() === 'checkout.store') {
            // Si la session a expiré mais que l'utilisateur soumet le formulaire
            if (!$request->session()->has('_token')) {
                // Sauvegarder les données du formulaire dans la session flash
                $request->session()->flash('old_checkout_data', $request->all());
                $request->session()->flash('cart_backup', session('cart', []));

                // Régénérer une nouvelle session
                $request->session()->regenerate();

                return redirect()->route('carrinho')
                    ->with('session_expired', true)
                    ->with('message', 'Votre session a expiré. Votre panier a été sauvegardé.');
            }
        }

        return $next($request);
    }
}
