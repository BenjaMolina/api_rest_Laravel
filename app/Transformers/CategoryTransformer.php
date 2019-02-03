<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => (int)$category->id,
            'titulo' => (string)$category->name,
            'detalle' => (string)$category->description,
            'fechaCreacion' => (string)$category->created_at,
            'fechaActualizacion' => (string)$category->updated_at,
            'fechaEliminacion' => isset($category->deleted_at) ? (string)$category->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id)
                ],
                [
                    'rel' => 'categories.product',
                    'href' => route('categories.products.index', $category->id)
                ],
                [
                    'rel' => 'categories.sellers',
                    'href' => route('categories.sellers.index', $category->id)
                ],
                [
                    'rel' => 'categories.transactions',
                    'href' => route('categories.transactions.index', $category->id)
                ],
                [
                    'rel' => 'categories.buyers',
                    'href' => route('categories.buyers.index', $category->id)
                ],
                
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'titulo' => 'name',
            'detalle' => 'description',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return ( isset($attributes[$index]) ?  $attributes[$index] : null );
    }
}
