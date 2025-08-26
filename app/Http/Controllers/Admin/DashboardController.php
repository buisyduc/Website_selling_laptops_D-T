<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\categorie;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\order_item;
use App\Models\categories;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    // Lấy khoảng ngày từ request
    $from = $request->input('from');
    $to = $request->input('to');

    // Query Order có điều kiện filter ngày
    $ordersQuery = Order::query();
    $usersQuery = User::query();

    if ($from && $to) {
        $ordersQuery->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        $usersQuery->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
    }

    // Doanh thu theo tháng
    $revenuesData = $ordersQuery->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
        ->groupBy('month')
        ->pluck('revenue', 'month')
        ->toArray();
    $revenuesData = array_replace(array_fill(1, 12, 0), $revenuesData);
    $revenuesData = array_values($revenuesData);

    // Mảng tháng cố định
    $months = range(1, 12);

    // Đơn hàng theo trạng thái
    $ordersByStatus = $ordersQuery->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

    // Khách hàng theo tháng
    $customersData = $usersQuery->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();
    $customersData = array_replace(array_fill(1, 12, 0), $customersData);
    $customersData = array_values($customersData);

    // Đơn hàng theo tháng
    $ordersData = $ordersQuery->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();
    $ordersData = array_replace(array_fill(1, 12, 0), $ordersData);
    $ordersData = array_values($ordersData);

    // Top sản phẩm bán chạy (lọc theo khoảng ngày nếu có)
    $topProductsQuery = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->join('products', 'products.id', '=', 'order_items.product_id');

    if ($from && $to) {
        $topProductsQuery->whereBetween('orders.created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
    }

    $topProducts = $topProductsQuery
        ->select('products.name as name', DB::raw('SUM(order_items.quantity) as total_qty'))
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total_qty')
        ->limit(5)
        ->get();

    // Chỉ tiêu doanh thu (tạm giữ nguyên theo tháng)
    $targets = [
        1 => 20000000,  
        2 => 25000000,
        3 => 30000000,
        4 => 28000000,
        5 => 35000000,
        6 => 40000000,
        7 => 45000000,
        8 => 42000000,
        9 => 50000000,
        10 => 55000000,
        11 => 60000000,
        12 => 70000000,
    ];
    $targets = array_replace(array_fill(1, 12, 0), $targets);
    $targets = array_values($targets);

    return view('admin.Dashboard.dashboard', compact(
        'months',
        'revenuesData',
        'ordersByStatus',
        'customersData',
        'ordersData',
        'topProducts',
        'targets',
        'from',
        'to'
    ));
}

}
