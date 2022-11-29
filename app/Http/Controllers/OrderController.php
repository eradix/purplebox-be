<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::orderByDesc('id')->get();

        return response()->json([
            "data" => $orders
        ]);
    }

    public function store(Request $request) {

        $totalPrice = $this->getTotalPrice($request->product_id, $request->quantity);

        $order = Order::create([
            "user_id" => Auth::id(),
            "product_id" => $request->product_id,
            "quantity" => $request->quantity,
            "total_price" => $totalPrice,
            "status" => $request->status,
            "message" => $request->message,
        ]);

        return response()->json([
            "message" => "Added to Cart",
            "data" => $order
        ]);
    }

    public function getUserCart() {
        $user = Auth::user();

        $cart = $user->orders;

        $totalOrder = $this->getTotalOrder($user->orders);

        return response()->json([
            "message" => "Fetch All Cart Success",
            "user" => $user,
            "total" => $totalOrder,
        ]);
    }

    public function delete($id) {
        $order = Order::where('id', $id)->first();

        $order->delete();

        return response()->json([
            'message' => 'Order has been deleted.',
        ]);
    }

    public function update(Request $request, $id) {
        $order = Order::where('id', $id)->first();

        $order->update($request->all());

        return response()->json([
            'message' => 'Order has been updated.',
            'data' => $order
        ]);
    }

    public function getTotalPrice($id, $qty) {
        $product = Product::where('id', $id)->first();
        $totalPrice = $product->price * $qty;
        return $totalPrice;
    }

    public function getTotalOrder($orders) {
        $total = 0;
        foreach($orders as $item) {
            $total += $item->total_price;
        }

        return $total;
    }

}
