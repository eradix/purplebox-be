<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::orderByDesc('id')->get();

        return response()->json([
            "data" => $orders
        ]);
    }

    public function update() {
        return "Order update";
    }
}
