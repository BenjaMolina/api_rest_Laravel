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
            'fechaCreacion' => (string)$user->created_at,
            'fechaActualizacion' => (string)$user->updated_at,
            'fechaEliminacion' => isset($user->deleted_at) ? (string)$user->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id)
                ],                
            ]
        ]; 
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'nombre' => 'name',
            'correo' => 'email',
            'esVerificado' => 'verified',
            'esAdmin' => 'admin',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return ( isset($attributes[$index]) ?  $attributes[$index] : null );
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'=> 'identifier',
            'name'=> 'nombre',
            'email'=> 'correo',
            'verified'=> 'esVerificado',
            'admin'=> 'esAdmin',
            'created_at'=> 'fechaCreacion',
            'updated_at'=> 'fechaActualizacion',
            'deleted_at'=> 'fechaEliminacion',
        ];

        return ( isset($attributes[$index]) ?  $attributes[$index] : null );
    }

}
