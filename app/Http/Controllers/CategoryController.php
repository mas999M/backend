<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return response()->json($category);
    }
    public function ca(Request $request)
    {
        $id = $request->id;


        $products = Product::where('category_id' , $id)->with('category')->get();

        Log::info($products);
//        // اضافه کردن آدرس کامل عکس به هر محصول
//        $products->getCollection()->transform(function ($product) {
//            $product->image_url = $product->image_path
//                ? asset('storage/' . $product->image_path)
//                : null;
//            return $product;
//        });

        return response()->json($products);
    }

    public function name($id)
    {
        $category = Category::find($id);
        $name = $category->name;
        return response()->json($name);
    }


}
