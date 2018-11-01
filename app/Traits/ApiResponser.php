<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
    private function succesResponse($data,$code)
    {
        return response()->json($data,$code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json([
            'error' => $message,
            'code' => $code
        ],$code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if($collection->isEmpty())
        {
            return $this->succesResponse(["data" => $collection],$code);
        }

        /*Obtenemos el primer conjunto de la colleccion para despues
        obtener el valor del atributo $transformer del modelo*/
        $transformer = $collection->first()->transformer;
        
        //Obtenemos los datos transformados usando el metodo nuevo
        $collection = $this->transformData($collection, $transformer);

        return $this->succesResponse($collection,$code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        //Obtenemos el valor del atributo $transformer del modelo
        $transformer = $instance->transformer;

        //Obtenemos los datos transformados usando el metodo nuevo
        $instance = $this->transformData($instance, $transformer);

        return $this->succesResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->succesResponse(['data' => $message], $code);
    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        
        return $transformation->toArray();
    }
}