<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Tools\ResponseCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
      /**
     * @OA\Post(
     * tags={"Login"},
     * path="/api/login",
     * description="Autenticacion",
     * operationId="login",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"email","password"},
     *                  @OA\Property(property="email", type="string", description="Email", example="adminp@pruebas.com"),
     *                  @OA\Property(property="password", type="string", description="Password", example="admin"),
     *       ),
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Response",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="json", example="User information"),
     *       @OA\Property(property="token", type="string", example="bearer token for user"),
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 0])) {

            Auth::logout();
            $message = 'Usuario No esta Activo';
            return $this->errorResponse($message, ResponseCodes::UNAUTHORIZED);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1])) {
        } else {
            $message = 'Credenciales Invalidas';
            return $this->errorResponse($message, ResponseCodes::UNAUTHORIZED);
        }

        $user = auth()->user();


        if ($user) {
            $accessToken = auth()->user()->createToken(env('TOKEN_SECRET'))->accessToken;
            return response(['user' => new UserResource($user), 'access_token' => $accessToken], ResponseCodes::OK);
        } else {
            Auth::logout();
            $message = 'El usuario no se encuentra';
            return $this->errorResponse($message, ResponseCodes::UNAUTHORIZED);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Login"},
     *     path="/api/v1/logout",
     *     description="Salir del sistema",
     *     security={{"token": {}}},
     *     operationId="logout",
     * @OA\Response(
     *    response=200,
     *    description="Successful Response",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="successful"),
     *       @OA\Property(property="message", type="string", example="User has been logged out"),
     *        )
     *     ),
     * * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['status' => 'successful', 'message' => 'El usuario ha cerrado sesión'], ResponseCodes::OK);
    }
}
