<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('client.products.index', compact('products'));
    }

    public function showById($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        return view('client.products.show', compact('product'));
    }
}
