<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identifier' => (int)$selller->id,
            'nombre' => (string)$selller->name,
            'correo' => (string)$selller->email,
            'esVerificado' => (int)$selller->verified,
            'fechaCreacion' => (string)$selller->created_at,
            'fechaActualizacion' => (string)$selller->updated_at,
            'fechaEliminacion' => isset($selller->deleted_at) ? (string)$selller->deleted_at : null,
        ];
    }
}
