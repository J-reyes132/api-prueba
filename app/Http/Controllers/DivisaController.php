<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Exceptions\SomethingWentWrong;
use App\Http\Resources\DivisaResource;
use App\Models\Divisa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DivisaController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Divisas"},
     *     path="/api/divisas",
     *     description="Listado de divisas",
     *     security={{"token": {}}},
     *     operationId="divisa_index",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nombre de divisa a buscar",
     *         required=false,
     *         @OA\Schema(type="string", example="EUR")
     *     ),
     *     @OA\Parameter(
     *         name="ordercreated",
     *         in="query",
     *         description="Ordenar fecha de Creación de la divisa",
     *         required=false,
     *         @OA\Schema(type="string", example="ASC")
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Response",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="[...]"),
     *        )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
     */

     public function index(Request $request)
     {
        try{
            $divisas = Divisa::name($request->name)
                ->orderCreated($request->ordercreated)
                ->active($request->active)
                ->paginate(10);
            return DivisaResource::collection($divisas);

        }catch(ModelNotFoundException $e){
            Handler::error();
        }
     }

        /**
     * @OA\Post(
     *     tags={"Divisas"},
     *     path="/api/divisa",
     *     description="Crear una divisa nueva",
     *     security={{"token": {}}},
     *     operationId="divisa_store",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"name",},
     *                  @OA\Property(property="name", type="string", description="Nombre de la divisa", example="dolar"),
     *                  @OA\Property(property="code", type="string", description="Código de la divisa", example="USD"),
     *                  @OA\Property(property="symbol", type="string", description="Simbolo de la divisa", example="$"),
     *                  @OA\Property(property="exchange_rate", type="number", format="float", description="Tasa de cambio de la divisa", example="1.23456789"),
     *                  @OA\Property(property="is_active", type="boolean", description="Estado de la divisa", example=true),
     *       ),
     *      ),
     *   ),
     * @OA\Response(
     *    response=201,
     *    description="Successful Stored",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="[...]"),
     *        )
     * ),
     *  @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:10',
            'exchange_rate' => 'nullable|numeric|min:0',
        ]);
        try {
            $divisa = new Divisa();
            $divisa->name = $request->name;
            $divisa->code = $request->code ?? strtoupper($request->name);
            $divisa->symbol = $request->symbol;
            $divisa->exchange_rate = $request->exchange_rate ?? 0.00000000;
            $divisa->is_active = $request->is_active;
            $divisa->save();

            return new DivisaResource($divisa);
        } catch (ValidationException $e) {
            Handler::errorCreacion();
        }
    }

    /**
     * @OA\Get(
     *     tags={"Divisas"},
     *     path="/api/divisa/{id}",
     *     description="Obtener una divisa por ID",
     *     security={{"token": {}}},
     *     operationId="divisa_show",
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Response",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="[...]"),
     *        )
     * ),
     *  @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
     */
    public function show(Divisa $divisa)
    {
        try {
            return new DivisaResource($divisa);
        } catch (ModelNotFoundException $e) {
            Handler::error();
        }
    }

    /**
     * @OA\Post(
     *     tags={"Divisas"},
     *     path="/api/divisa/{id}/update",
     *     description="Actualizar una divisa",
     *     security={{"token": {}}},
     *     operationId="divisa_update",
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"name",},
     *                  @OA\Property(property="name", type="string", description="Nombre de la divisa", example="dolar"),
     *                  @OA\Property(property="code", type="string", description="Código de la divisa", example="USD"),
     *                  @OA\Property(property="symbol", type="string", description="Simbolo de la divisa", example="$"),
     *                  @OA\Property(property="exchange_rate", type="number", format="float", description="Tasa de cambio de la divisa", example="1.23456789"),
     *       ),
     *      ),
     *   ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Updated",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="[...]"),
     *        )
     * ),
     *  @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
    */

    public function update(Request $request, Divisa $divisa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:10',
            'exchange_rate' => 'nullable|numeric|min:0',
        ]);
        try {
            $divisa->name = $request->name;
            $divisa->code = $request->code ?? strtoupper($request->name);
            $divisa->symbol = $request->symbol;
            $divisa->exchange_rate = $request->exchange_rate ?? 0.00000000;
            $divisa->is_active = $request->is_active;
            $divisa->save();

            return new DivisaResource($divisa);
        } catch (ModelNotFoundException $e) {
            return Handler::errorCreacion('Error al actualizar el recurso.'.$e ->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Divisas"},
     *     path="/api/divisa/{id}/delete",
     *     description="Eliminar una divisa",
     *     security={{"token": {}}},
     *     operationId="divisa_destroy",
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     *    response=204,
     *    description="Successful Deleted"
     * ),
     *  @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
    */
    public function destroy(Divisa $divisa)
    {
        try {
            if ($divisa->is_active) {
                return Handler::errorActivo();
            }
            $divisa->delete();
            return Handler::successDelete();
        } catch (ModelNotFoundException $e) {
            return Handler::error();
        }
    }

 /**
     * @OA\Post(
     *     tags={"Divisas"},
     *     path="/api/backoffice/v1/divisa/{divisa}/toggle",
     *     description="Activar o desactivar una divisa",
     *     security={{"token": {}}},
     *     operationId="divisa_toggle",
     * @OA\Parameter(
     *          name="divisa",
     *          in="path",
     *          description="divisa Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Response(
     *    response=201,
     *    description="Successful Activated or Deactivated",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="[...]"),
     *        )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Error recurso no encontrado",
     *    @OA\JsonContent(@OA\Property(property="status", type="string", example="error"),
     *                     @OA\Property(property="message", type="string", example="Recurso no encontrado"),
     *        )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
     */
    public function toggle(Divisa $divisa)
    {

        try {
            $divisa->is_active = !$divisa->is_active;
            $divisa->save();

            return new DivisaResource($divisa);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}
