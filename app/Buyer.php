<?php

namespace App;

use App\Transaccion;


class Buyer extends User
{
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
