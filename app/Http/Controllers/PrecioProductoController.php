<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Http\Resources\PrecioProductoFullResource;
use App\Http\Resources\PrecioProductoResource;
use App\Models\PrecioProducto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PrecioProductoController extends Controller
{
        /**
     * @OA\Get(
     *     tags={"Precio Productos"},
     *     path="/api/precio-productos",
     *     description="Listado de precios de productos",
     *     security={{"token": {}}},
     *     operationId="precio_producto_index",
     *     @OA\Parameter(
     *         name="producto_id",
     *         in="query",
     *         description="ID del producto a buscar",
     *         required=false,
     *         @OA\Schema(type="integer", example="1")
     *     ),
     *      *     @OA\Parameter(
     *         name="divisa_id",
     *         in="query",
     *         description="ID de la divisa a buscar",
     *         required=false,
     *         @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="ordercreated",
     *         in="query",
     *         description="Ordenar fecha de Creación del precio del producto",
     *         required=false,
     *         @OA\Schema(type="string", example="ASC")
     *     ),
     *      *     @OA\Parameter(
     *         name="is_active",
     *         in="query",
     *         description="Filtrar por estado del precio del producto",
     *         required=false,
     *         @OA\Schema(type="string", example="true")
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
            $precio_productos = PrecioProducto::productoId($request->producto_id)
                ->divisaId($request->divisa_id)
                ->orderCreatedBy($request->ordercreated)
                ->active($request->is_active)
                ->paginate(10);
            return PrecioProductoResource::collection($precio_productos);

        }catch(ModelNotFoundException $e){
            Handler::error();
        }
     }

        /**
     * @OA\Post(
     *     tags={"Precio Productos"},
     *     path="/api/precio-producto",
     *     description="Crear un precio de producto nuevo",
     *     security={{"token": {}}},
     *     operationId="precio_producto_store",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"producto_id","divisa_id","precio"},
     *                  @OA\Property(property="producto_id", type="integer", description="ID del producto", example="1"),
     *                  @OA\Property(property="divisa_id", type="integer", description="ID de la divisa", example="1"),
     *                  @OA\Property(property="precio", type="number", format="float", description="Precio del producto", example="1500.00"),
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
            'producto_id' => 'required|exists:productos,id',
            'divisa_id' => 'required|exists:divisas,id',
            'precio' => 'required|numeric|min:0',

        ]);
        try {
            $precioProducto = new PrecioProducto();
            $precioProducto->producto_id = $request->producto_id;
            $precioProducto->divisa_id = $request->divisa_id;
            $precioProducto->precio = $request->precio;
            $precioProducto->save();

            return new PrecioProductoResource($precioProducto);
        } catch (ValidationException $e) {
            Handler::errorCreacion();
        }
    }

    /**
     * @OA\Get(
     *     tags={"Precio Productos"},
     *     path="/api/precio-producto/{precioProducto}/show",
     *     description="Obtener un precio de producto por ID",
     *     security={{"token": {}}},
     *     operationId="precio_producto_show",
     * @OA\Parameter(
     *         name="precioProducto",
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
    public function show(PrecioProducto $precioProducto)
    {
        try {
            return new PrecioProductoFullResource($precioProducto);
        } catch (ModelNotFoundException $e) {
             return $this->errorResponse('Recurso no encontrado', ResponseCodes::NOT_FOUND, ['error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Precio Productos"},
     *     path="/api/precio-producto/{precioProducto}/update",
     *     description="Actualizar un precio de producto",
     *     security={{"token": {}}},
     *     operationId="precio_producto_update",
     * @OA\Parameter(
     *         name="precioProducto",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
        *       @OA\Schema( required={"producto_id","divisa_id","precio"},
        *                  @OA\Property(property="producto_id", type="integer", description="ID del producto", example="1"),
        *                  @OA\Property(property="divisa_id", type="integer", description="ID de la divisa", example="1"),
        *                  @OA\Property(property="precio", type="number", format="float", description="Precio del producto", example="1500.00"),
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

    public function update(Request $request, PrecioProducto $precioProducto)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'divisa_id' => 'required|exists:divisas,id',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            $precioProducto->producto_id = $request->producto_id;
            $precioProducto->divisa_id = $request->divisa_id;
            $precioProducto->precio = $request->precio;
            $precioProducto->save();

            return new PrecioProductoResource($precioProducto);
        } catch (ModelNotFoundException $e) {
             return Handler::errorCreacion('Error al actualizar el recurso.'.$e ->getMessage());
        }
    }




    /**
     * @OA\Delete(
     *     tags={"Precio Productos"},
     *     path="/api/precio-producto/{precioProducto}/delete",
     *     description="Eliminar un precio de producto",
     *     security={{"token": {}}},
     *     operationId="precio_producto_destroy",
     * @OA\Parameter(
     *         name="precioProducto",
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
    public function destroy(PrecioProducto $precioProducto)
    {
        try {
            if ($precioProducto->is_active) {
                return $this->errorResponse('No se puede eliminar un precio de producto activo', ResponseCodes::UNPROCESSABLE_ENTITY);
            }
            $precioProducto->delete();
            return Handler::successDelete();
        } catch (\Throwable $th) {
                throw new SomethingWentWrong($th);
        }
    }

 /**
     * @OA\Post(
     *     tags={"Precio Productos"},
     *     path="/api/precio-producto/{precioProducto}/toggle",
     *     description="Activar o desactivar un precio de producto",
     *     security={{"token": {}}},
     *     operationId="precio_producto_toggle",
     * @OA\Parameter(
     *          name="precioProducto",
     *          in="path",
     *          description="precio Producto Id",
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
    public function toggle(PrecioProducto $precioProducto)
    {

        try {
            $precioProducto->is_active = !$precioProducto->is_active;
            $precioProducto->save();
            return new PrecioProductoResource($precioProducto);
        } catch (NotFoundException $e) {
           return $this->errorResponse('Recurso no encontrado', ResponseCodes::NOT_FOUND, ['error' => $e->getMessage()]);
        }
    }
}
