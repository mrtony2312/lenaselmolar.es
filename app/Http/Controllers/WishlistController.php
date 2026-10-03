<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|string',
                'product_title' => 'required|string',
                'product_price' => 'required|string',
                'product_image' => 'required|string',
                'product_slug' => 'required|string',
            ]);

            $productId = $validated['product_id'];

            $wishlist = Session::get('wishlist', []);

            // Vérifier si le produit n'existe pas déjà
            if (!isset($wishlist[$productId])) {
                $wishlist[$productId] = [
                    'id' => $productId,
                    'title' => $validated['product_title'],
                    'price' => $validated['product_price'],
                    'image' => $validated['product_image'],
                    'slug' => $validated['product_slug'],
                    'added_at' => now()->toDateTimeString()
                ];

                Session::put('wishlist', $wishlist);

                return response()->json([
                    'success' => true,
                    'message' => 'Producto añadido a tu lista de deseos',
                    'count' => count($wishlist)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'El producto ya está en tu lista de deseos',
                'count' => count($wishlist)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha podido actualizar la lista de deseos.'
            ], 500);
        }
    }

    public function remove(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|string',
            ]);

            $productId = $validated['product_id'];
            $wishlist = Session::get('wishlist', []);

            if (isset($wishlist[$productId])) {
                unset($wishlist[$productId]);
                Session::put('wishlist', $wishlist);

                return response()->json([
                    'success' => true,
                    'message' => 'Producto eliminado de tu lista de deseos',
                    'count' => count($wishlist)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'El producto no está en tu lista de deseos'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha podido actualizar la lista de deseos.'
            ], 500);
        }
    }

    public function index()
    {
        $wishlist = Session::get('wishlist', []);
        $allProducts = collect(config('loja_products', []));

        $wishlistProducts = [];
        foreach ($wishlist as $item) {
            $product = $allProducts->firstWhere('id', $item['id']);
            if ($product) {
                $wishlistProducts[] = array_merge($product, [
                    'added_at' => $item['added_at'] ?? now()->toDateTimeString()
                ]);
            }
        }

        return view('lista-de-desejos', [
            'wishlistProducts' => $wishlistProducts,
            'wishlistCount' => count($wishlist)
        ]);
    }

    public function getCount()
    {
        $wishlist = Session::get('wishlist', []);
        return response()->json(['count' => count($wishlist)]);
    }
}
