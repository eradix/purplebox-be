<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        if($request->type == "All") {
            $products = Product::orderByDesc("id")->get();

            return response()->json([
                "data" => $products
            ]);
        }

        $products = Product::orderByDesc("id")->where('type', $request->type)->get();

        return response()->json([
            "data" => $products
        ]);
    }

    public function store(Request $request)
    {
        $image = "";
        if($request->hasFile('image')) {
            // $image = $request->image->store('cakes');
            // $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            // $img->save();
            $image = $request->file('image')->store('cakes', 'public');

        }

        $isBestSeller = (int)$request->is_best_seller;

        $product = Product::create([
            "name" => $request->name,
            "type" => $request->type,
            "image" => $image,
            "description" => $request->description,
            "price" => $request->price,
            "is_best_seller" => $isBestSeller,
        ]);

        return response()->json([
            "message" => "Product has been added.",
            "data" => $product,
        ], 201);
    }

    
    public function show(Product $product)
    {
        return response()->json([
            "data" => $product,
        ], 200);
    }

    public function update(Request $request, Product $product)
    {
        if($request->hasFile("image")) {
            Storage::disk()->delete($product->image);
            // $image = $request->image->store('cakes');
            // $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            // $img->save();
            $image = $request->file('image')->store('cakes', 'public');
            // $product->image = $image;
            // $product->save();
        }

        $product->update([
            "name" => $request->name,
            "type" => $request->type,
            "description" => $request->description,
            "price" => $request->price,
            "image" => $image
        ]);

        return response()->json([
            "message" => "Product has been updated.",
            "data" => $product
        ], 200);
    }

    public function destroy(Request $request, Product $product)
    {
        Storage::disk()->delete($product->image);
        $product->delete();

        return response()->json([
            "message" => "Product has been deleted.",
        ], 200);
    }

    public function searchProduct(Request $request){
        $query = $request->input('query');
        $type = $request->input('type');

        $product_query = Product::query();

        if ($query) {
            $product_query->where('name', 'like', '%' . $query . '%');
        }

        if ($type) {
            $product_query->where('type', $type);
        }

        $products = $product_query->orderBy('name')->get();

        return response()->json([
            "data" => $products
        ]);
    }

    public function getBestSeller(){
        $bestSeller = Product::where('is_best_seller', 1)->get();
        
        return response()->json([
            "data" => $bestSeller
        ]);
    }
}
