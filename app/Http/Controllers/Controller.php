<?php

namespace App\Http\Controllers;

use App\Tools\MailChimpTrait;
use App\Tools\MinioBucketTrait;
use App\Tools\NotificacionTrait;
use App\Tools\ToolsTrait;
use App\Tools\ValidadorTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Portal 311",
 *    version="2.0",
 *    description="Documentación API Portal 311",
 *   @OA\Contact(
 *       name="API Support",
 *       url= "http://www.ogtic.gob.do",
 *       email = "developer@ogtic.gob.do",
 *   ),
 * ),
 * @OA\SecurityScheme(
 *   securityScheme="token",
 *   type="http",
 *   name="Authorization",
 *   in="header",
 *   scheme="Bearer"
 * )
 */
class Controller extends BaseController
{
     use AuthorizesRequests, ValidatesRequests, ToolsTrait, MinioBucketTrait, NotificacionTrait, ValidadorTrait, MailChimpTrait;

         protected function errorResponse($message = null, $status = 500)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        return response()->json($response, $status);
    }
}
