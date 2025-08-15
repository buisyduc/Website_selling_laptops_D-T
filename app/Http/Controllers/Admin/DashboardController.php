<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\order_item;
use App\Models\categories;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.Dashboard.dashboard');
    }

    public function getData(Request $request)
    {
        // Bộ lọc thời gian
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();

         // 1. Doanh thu theo thời gian
        $revenueData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 2. Top sản phẩm bán chạy
            $topProducts = order_item::select('product_id', DB::raw('SUM(quantity) as total_qty'))
                ->with('product:id,name')
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->take(5)
                ->get();

         // 3. Top khách hàng mua nhiều nhất
        $topCustomers = Order::select('user_id', DB::raw('SUM(total_amount) as total_spent'))
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        // 4. Đơn hàng gần đây
        $recentOrders = Order::with('user:id,name')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // 5. Danh mục bán chạy
        $topCategories = categories::select('categories.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('products', 'products.category_id', '=', 'categories.id')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return response()->json([
            'revenueData' => $revenueData,
            'topProducts' => $topProducts,
            'topCustomers' => $topCustomers,
            'recentOrders' => $recentOrders,
            'topCategories' => $topCategories
        ]);
    }
}
