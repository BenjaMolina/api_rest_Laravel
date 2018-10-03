<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()
                        //transaction a product 
                        //y product a seller
                         ->with('product.seller')
                         ->get()
                         ->pluck('product.seller')
                         ->unique('id') //que no se repita el id
                         ->values(); //reordena

        return $this->showAll($sellers);
    }
}
