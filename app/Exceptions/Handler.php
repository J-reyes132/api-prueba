<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use App\Exceptions\ExceptionTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{

    use ExceptionTrait;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof AuthorizationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 403,
                    'message' => 'No tienes permisos para realizar esta acción.',
                ], 403);
            }

            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción.');
        }

        if ($request->expectsJson()) {
            return $this->apiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    public static function error($message = 'Recurso no encontrado.',$statusCode = '404',$data = []){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => true,
            'data' => $data
            ],$statusCode);
    }

    public static function duplicado($message = 'Recurso duplicado.',$statusCode = '422',$data = []){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => true,
            'data' => $data
            ],$statusCode);
    }

    public static function errorCreacion($message = 'Error al crear el recurso.',$statusCode = '422'){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => true
            ],$statusCode);
    }

    public static function success($message = 'Requerimiento completado sin incidencias',$statusCode = 200){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => false
            ],$statusCode);
    }

    public static function errorActivo($message = 'No se puede borrar recurso activo.',$statusCode = '400'){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => true
            ],$statusCode);
    }

    public static function errorDeleteAssigned($message = 'No se puede borrar recurso activo o asignado.',$statusCode = '400'){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => true
            ],$statusCode);
    }

    public static function successDelete($message = 'Recurso eliminado',$statusCode = 200){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => false
            ],$statusCode);
    }

    public static function errorUpdate($message = 'la contraseña actual no coincide con la contraseña del usuario.',$statusCode = '422'){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => true
            ],$statusCode);
    }

    public static function updateCaso($message = 'No perteneces a la institución que puede editar este caso',$statusCode = 403){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => false
            ],$statusCode);
    }

    public static function acceso($message = 'No tienes permisos para realizar esta acción.',$statusCode = 403){
        return response()->json([
            'message' => $message,
            'statusCode' => $statusCode,
            'error' => false
            ],$statusCode);
    }
}
