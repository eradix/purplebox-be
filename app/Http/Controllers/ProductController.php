<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Image;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::orderByDesc("id")->get();
        return response()->json([
        "data" => $products
    ]);
    }

    public function store(Request $request)
    {
        if($request->hasFile('image')) {
            $image = $request->image->store('cakes');
            $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            $img->save();
        }

        $product = Product::create([
            "name" => $request->name,
            "type" => $request->type,
            "image" => $image,
            "description" => $request->description,
            "price" => $request->price,
        ]);

        return response()->json([
            "message" => "Product has been added.",
            "data" => $product,
        ], 201);
    }

    
    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
