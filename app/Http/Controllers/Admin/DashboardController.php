<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\categorie;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\order_item;
use App\Models\categories;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index()
{
    $startDate = Carbon::now()->startOfMonth();
    $endDate = Carbon::now()->endOfMonth();

    $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

    $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

    $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('status')
        ->pluck('total','status');

    $totalProductsSold = order_item::whereHas('order', function($q) use ($startDate, $endDate){
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('quantity');

    $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

    $newCustomers = \App\Models\User::whereBetween('created_at', [$startDate, $endDate])->count();

    $topProducts = order_item::select('product_id', DB::raw('SUM(quantity) as total_qty'))
        ->with('product:id,name')
        ->groupBy('product_id')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();

    $topCustomers = Order::select('user_id', DB::raw('SUM(total_amount) as total_spent'))
        ->with('user:id,name')
        ->groupBy('user_id')
        ->orderByDesc('total_spent')
        ->take(5)
        ->get();

    $recentOrders = Order::with('user:id,name')
        ->orderByDesc('created_at')
        ->take(5)
        ->get();

    $topCategories = categorie::select('categories.name', DB::raw('SUM(order_items.quantity) as total_sold'))
        ->join('products', 'products.category_id', '=', 'categories.id')
        ->join('order_items', 'order_items.product_id', '=', 'products.id')
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('total_sold')
        ->take(5)
        ->get();

    return view('admin.Dashboard.dashboard', compact(
        'totalRevenue','totalOrders','ordersByStatus','totalProductsSold','avgOrderValue','newCustomers',
        'topProducts','topCustomers','recentOrders','topCategories'
    ));
}


}
