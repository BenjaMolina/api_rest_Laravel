<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier' => (int)$product->id,
            'nombre' => (string)$product->name,
            'descripcion' => (string)$product->description,
            'disponible' => (int)$product->name,
            'estado' => (string) $product->status,
            'imagen' => url("img/{$product->image}"),
            'vendedor' => (int)$product->seller_id,
            'fechaCreacion' => (string)$product->created_at,
            'fechaActualizacion' => (string)$product->updated_at,
            'fechaEliminacion' => isset($product->deleted_at) ? (string)$product->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id)
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index', $product->id)
                ],
                [
                    'rel' => 'product.categories',
                    'href' => route('products.categories.index', $product->id)
                ],

                //Vamos directamente a ver al vendedor
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $product->seller_id)
                ],
                
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'nombre' => 'name',
            'descripcion' => 'description',
            'disponible' => 'name',
            'estado' => 'status',
            'imagen' => 'image',
            'vendedor' => 'seller_id',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return ( isset($attributes[$index]) ?  $attributes[$index] : null );
    }
}
