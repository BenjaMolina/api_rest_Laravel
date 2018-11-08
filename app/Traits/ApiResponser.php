<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

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
        
        //filtramos la colleccion de acuerdo a los parametros
        $collection =  $this->filterData($collection,$transformer);
        
        //Ordenamos la coleccion antes de ser transformada
        $collection =  $this->sortData($collection, $transformer);
        
        //Paginamos la respuesta
        $collection = $this->paginate($collection);

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

    protected function sortData(Collection $collection, $transformer)
    {
        if(request()->has('sort_by')){
            $attribute = $transformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy($attribute);
        }
        return $collection;
    }

    protected function filterData($collection, $transformer)
    {
        foreach (request()->all() as $attribute => $value) {
            $originalAttribute = $transformer::originalAttribute($attribute);
            
            if(isset($originalAttribute, $value)){
                $collection = $collection->where($originalAttribute,$value);
            }
            
        }

        return $collection;
    }

    protected function paginate(Collection $collection)
    {
        Validator::validate(request()->all(),[
            'per_page' => 'integer|min:2|max:50'
        ]);

        //Resuelve la pagina que nos encontramos (actual)
        $page = LengthAwarePaginator::resolveCurrentPage();
        
        //Numero de elementos por pagina
        $perPage = 15;
        if(request()->has('per_page')){
            $perPage = (int) request()->per_page;
        }
        //Dividimos la coleccion ( inicio - fin ) 
        $results = $collection->slice(( $page - 1) * $perPage,$perPage)->values();
        

        //Creamos la paginacion
        $paginated = new LengthAwarePaginator(
            $results, //datos a mostrar
            $collection->count(), //Total de elementos
            $perPage, //Elementos por pagina
            $page, //Pagina actual
            [ //Opciones
                //Muestra pagina siguiente y anterior
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);

        //Agregamos todos parametros pasados por URL
        $paginated->appends(request()->all());

        return $paginated;
    }
}