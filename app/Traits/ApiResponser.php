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
        return repsonse()->json([
            'error' => $message,
            'code' => $code
        ],$code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->succesResponse(['data' => $collection],$code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        return $this->succesResponse(['data' => $instance], $code);
    }
}