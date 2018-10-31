<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int)$user->id,
            'nombre' => (string)$user->name,
            'correo' => (string)$user->email,
            'esVerificado' => (int)$user->verified,
            'esAdmin' => ($user->admin === 'true'),
            'fechaCreacion' => $user->created_at,
            'fechaActualizacion' => $user->updated_at,
            'fechaEliminacion' => $user->deleted_at,
        ]; 
    }
}
