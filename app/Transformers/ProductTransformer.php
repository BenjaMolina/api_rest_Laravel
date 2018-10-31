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
            'imagen' => url("img/{$user->image}"),
            'vendedor' => (int)$product->seller_id,
            'fechaCreacion' => (string)$category->created_at,
            'fechaActualizacion' => (string)$product->updated_at,
            'fechaEliminacion' => isset($product->deleted_at) ? (string)$product->deleted_at : null,
        ];
    }
}
