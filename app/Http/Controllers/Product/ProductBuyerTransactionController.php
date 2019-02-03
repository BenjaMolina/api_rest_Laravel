<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;
use App\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('transform.input:'. TransactionTransformer::class)->only(['store']);        
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $this->validate($request,[
            'quantity' => 'required|integer|min:1',
        ]);

        if($buyer->id == $product->seller_id)
        {
            return $this->errorResponse('El comprador debe ser diferente al vendedor', 409);
        }

        if(!$buyer->esVerficado())
        {
            return $this->errorResponse('El comprador debe ser un usuario verificado', 409);
        }
        if(!$product->seller->esVerficado())
        { 
            return $this->errorResponse('El vendedor debe ser un usuario verificado', 409);
        }
        if(!$product->estaDisponible())
        {
            return $this->errorResponse('El producto para esta transaccion no esta disponible', 409);
        }
        if($product->quantity < $request->quantity)
        {
            return $this->errorResponse('El producto no tiene la cantidad disponible requerida para esta transaccion', 409);
        }


        return DB::transaction(function() use ($request, $product, $buyer){
            
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });

    }
}
