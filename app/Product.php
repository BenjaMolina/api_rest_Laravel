<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Category;
use App\Seller;
use App\Transaction;

class Product extends Model
{
    const PRODUCT_DISPONIBLE = 'disponible';
    const PRODUCT_NO_DISPONIBLE = 'no disponible';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',

    ];

    public function estaDisponible()
    {
        return $this->status == Product::PRODUCT_DISPONIBLE;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
