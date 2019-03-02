<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //

    public function index()
    {
        $products = Product::all();

        return view('products', compact('products'));    }

    public function cart()
    {
        return view('cart');
    }

    public function addToCart($id){

        $product = Product::find($id);

        if (!$product){
            abort(404);
        }

        $cart = session()->get('cart');

        if (!$cart){
            $cart = [
                $id => [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "photo" => $product->photo
                ]
            ];

            session()->put('cart',$cart);
            return redirect()->back()->with('success' , 'Product added to cart');
        }

        // if cart not empty and check  this product has this cart then increment this product.

        if (isset($cart[$id])){
            $cart[$id]['quantity']++;
            session()->put('cart',$cart);
            return redirect()->back()->with('success' , 'Product added to cart');
        }


        // if cart not empty and added another product

        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price,
            "photo" => $product->photo
        ];

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully!');

    }

    public function update(Request $request){
        if ($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Cart updated successfully');
        }
    }

    public function removed(Request $request){
        if ($request->id){
            $cart = session()->get('cart');
            if (isset($cart[$request->id])){
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'cart updated successfully');
        }
    }
}
