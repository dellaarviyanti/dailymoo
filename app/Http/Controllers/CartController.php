<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display cart page
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $product->price * $item['quantity'];
                $total += $item['subtotal'];
                $cartItems[$productId] = $item;
            }
        }

        return view('shop.cart', compact('cartItems', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($validated['product_id']);

        // Check stock
        if ($product->stock < $validated['quantity']) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = Session::get('cart', []);

        // If product already in cart, update quantity
        if (isset($cart[$validated['product_id']])) {
            $cart[$validated['product_id']]['quantity'] += $validated['quantity'];
            
            // Check if total quantity exceeds stock
            if ($cart[$validated['product_id']]['quantity'] > $product->stock) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Stok tidak mencukupi.');
            }
        } else {
            $cart[$validated['product_id']] = [
                'quantity' => $validated['quantity'],
            ];
        }

        Session::put('cart', $cart);

        // Calculate new cart count
        $cartCount = count($cart);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => $cartCount,
                'product_name' => $product->name
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Get cart count (for AJAX requests)
     */
    public function getCount()
    {
        $cart = Session::get('cart', []);
        return response()->json([
            'count' => count($cart)
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        if ($validated['quantity'] > $product->stock) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            if ($validated['quantity'] == 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $validated['quantity'];
            }
        }

        Session::put('cart', $cart);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui!',
                'cart_count' => count($cart)
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Remove item from cart
     */
    public function remove($productId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
    }
}

