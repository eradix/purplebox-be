<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request) {
        $orders = Order::with('user', 'product')->where('status', $request->status)->orderByDesc('id')->get();

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

    public function show($id) {
        $data = Order::where('id', $id)->with('user', 'product')->first();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getUserCart(Request $request) {
        $user = Auth::user();
    
        $userOrders = $user->orders->where('status', $request->status);
        $totalOrder = $this->getTotalOrder($userOrders);

        return response()->json([
            "message" => "Fetch All Cart Success",
            "data" => !empty($userOrders['1']) ? array($userOrders['1']) : $userOrders,
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

    public function updateAddToCart(Request $request, $id) {
        $order = Order::where('product_id', $id)->with('product')->first();
        $qty = $order->quantity + $request->quantity;
        $totalPrice = $qty * $order->product->price;

        $order->update([
            'quantity' => $qty,
            'total_price' => $totalPrice
        ]);

        return response()->json([
            'message' => "Product Added to Cart",
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

    public function getTotalOfAllItems() {
        $user = Auth::user();
        $total = 0;

        foreach($user->orders as $item) {
            $total += $item->total_price;
        }

        foreach($user->customCakes as $item) {
            $total += $item->price * $item->quantity;
        }

        return response()->json([
            'message' => "Total Price of all items in the cart",
            'totalPrice' => $total,
        ]);
    }
}
