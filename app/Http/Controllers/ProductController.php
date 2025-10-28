<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product(Request $request)
    {


        // 📂 ذخیره عکس (اگه وجود داشت)
        $filePath = null;
        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('images', 'public');
        }

        // 📦 ذخیره محصول در دیتابیس
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->sendCategory,
            'price' => $request->price,
            'image_path' => $filePath, // فقط مسیر ذخیره میشه
        ]);

        // 📸 ساخت URL کامل برای نمایش در فرانت (اختیاری)
        $url = $filePath ? asset('storage/' . $filePath) : null;

        // 💬 پاسخ (API یا Redirect)
        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product,
            'image_url' => $url,
        ]);
    }

    public function category()
    {
        $category = Category::all();
        return response()->json($category);
    }
}
