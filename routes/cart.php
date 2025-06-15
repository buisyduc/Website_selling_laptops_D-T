<?php

use Illuminate\Support\Facades\Route;

Route::get('/cart/summary', function () {
    $user = auth()->user();

    if (!$user || !$user->cart) {
        return response()->json([
            'success' => true,
            'total_quantity' => 0,
            'total_amount' => '0₫'
        ]);
    }

    $cart = $user->cart;
    $totalQty = $cart->items()->sum('quantity');
    $totalAmount = 0;

    foreach ($cart->items as $item) {
        if ($item->variant) {
            $totalAmount += $item->quantity * $item->variant->price;
        }
    }

    return response()->json([
        'success' => true,
        'total_quantity' => $totalQty,
        'total_amount' => number_format($totalAmount, 0, ',', '.') . '₫'
    ]);
})->name('cart.summary');
