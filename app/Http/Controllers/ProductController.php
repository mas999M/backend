<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function product(Request $request)
    {


        // 📂 ذخیره عکس (اگه وجود داشت)
        $filePath = null;
        if ($request->hasFile('image')) {
            Log::info('File exists!');
            $filePath = $request->file('image')->store('images', 'public');
        }else{
            Log::warning('No file uploaded.');
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
    public function products()
    {
        // محصولات را با paginate بگیریم، مثلا 10 تا در هر صفحه
        $products = Product::paginate(8);

        // اضافه کردن آدرس کامل عکس به هر محصول
        $products->getCollection()->transform(function ($product) {
            $product->image_url = $product->image_path
                ? asset('storage/' . $product->image_path)
                : null;
            return $product;
        });

        return response()->json($products);
    }


}
