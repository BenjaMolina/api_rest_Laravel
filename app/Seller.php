<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Product;

class Seller extends User
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
