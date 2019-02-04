<?php

namespace App\Http\Middleware;

use Closure;

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
        $transformedInput = [];

        //Obtenemos solo los atributos de los input
        foreach ($request->request->all() as $input => $value) {
            //Obtiene el atributo original
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }
        //Reemplazamos los atributos originales
        $request->replace($transformedInput);

        return $next($request);
    }
}
