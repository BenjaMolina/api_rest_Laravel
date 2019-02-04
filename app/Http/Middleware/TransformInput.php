<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        /* Transformando inputs a valores originales */
        $transformedInput = [];

        //Obtenemos solo los atributos de los input
        foreach ($request->request->all() as $input => $value) {
            //Obtiene el atributo original
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }
        //Reemplazamos los atributos originales
        $request->replace($transformedInput);


        /* Tranformando de originales a tranformer */
        $response = $next($request);

        if(isset($response->exception) && $response->exception instanceof ValidationException){
            $data = $response->getData();

            $transformedErrors = [];
            foreach ($data->error as $field => $error) {
                $transformedField = $transformer::transformedAttribute($field);
                $transformedErrors[$transformedField] = str_replace($field,$transformedField, $error);
            }

            $data->error = $transformedErrors;

            $response->setData($data);
        }

        return $response;
    }
}
