<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;  // import model Product

class HomeController extends Controller
{
    public function index(){
        // Lấy danh sách sản phẩm, ví dụ lấy 12 sản phẩm mới nhất
        $products = Product::latest()->take(12)->get();

        // Truyền $products sang view client.index
        return view('client.index', compact('products'));
    }
}