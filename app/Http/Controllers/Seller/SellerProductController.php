<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\User;
use App\Product;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $this->validate($request,[
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
            // 'status',
            // 'seller_id',
        ]);

        $data = $request->all();

        $data['status'] = Product::PRODUCT_NO_DISPONIBLE;
        $data['image'] = '1.jpg';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product,201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        
        $this->validate($request,[
            'quantity' => 'integer|min:1',
            'status' => 'in:'.Product::PRODUCT_DISPONIBLE . ','. Product::PRODUCT_NO_DISPONIBLE,
            'image' => 'image',
        ]);

        //Verificamos que sean el mismo ID
        if($seller->id != $product->seller_id)
        {
            return $this->errorResponse('El vendedor espicificado no es el vendedor real del producto',422);
        }

        $product->fill($request->intersect([
            'name',
            'description',
            'quantity'
        ]));

        if($request->has('status'))
        {
            
            $product->status = $request->status;

            if($product->estaDisponible() && $product->categories()->count() == 0){
                return $this->errorResponse('Un producto activo debe tener al menos una categoria asociada',409);
            }
        }

        if($product->isClean())
        {
            return $this->errorResponse('Se debe especificar al  menos un valor diferente para actualizar', 422);
        }

        $product->save();

        return $this->showOne($product);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller)
    {
        //
    }
}
