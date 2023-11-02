<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait ApiResponser
{
    //Método privado utilizado al retornar una respuesta éxitosa
    private function successResponse($data, $code)
    {
        return response()->json($data, $code, [], JSON_NUMERIC_CHECK);
    }

    //Generalizando la respuesta de error de la api
    protected function errorResponse($errorTitle, $errorBody, $code)
    {
        return response()->json([
            'error_title'=>$errorTitle,
            'error_body'=>$errorBody,
        ], $code);
    }

    //Generalizando la respuesta de la api cuando se retorna un array de objetos
    protected function showAll(Collection $collection, $code = 200)
    {
        $collection = $this->cacheResponse($collection);
        return $this->successResponse($collection, $code);
    }

    //Generalizando la respuesta de la api cuando se retorna un objeto
    protected function showOne(Model $instance, $code = 200)
    {
        return $this->successResponse($instance, $code);
    }

    //Método encargado de retornar un mensaje 
    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data'=>$message], $code);
    }

    protected function unauthorizedResponse() {
        return $this->errorResponse('No autorizado', 'Este usuario no posee permisos para realizar esta acción', 403);
    }

    //Método encargado de almacenar una respuesta en cache por 15 segundos
    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 15/60, function() use($data) {
            return $data;
        });
    }
}