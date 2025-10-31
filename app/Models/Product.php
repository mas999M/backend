<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image_path',
        'category_id',
    ];

    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function CartItems()
    {
        return $this->hasMany(CartItem::class ,'product_id');
    }
    public function category()
    {

        return $this->belongsTo(Category::class);
    }

    //    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($product) {
//            $product->slug = Str::slug($product->name);
//        });
//    }

}
