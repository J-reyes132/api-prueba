<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Exceptions\SomethingWentWrong;
use App\Http\Resources\ProductoFullResource;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use App\Tools\ResponseCodes;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Productos"},
     *     path="/api/productos",
     *     description="Listado de productos",
     *     security={{"token": {}}},
     *     operationId="producto_index",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nombre de producto a buscar",
     *         required=false,
     *         @OA\Schema(type="string", example="test")
     *     ),
     *     @OA\Parameter(
     *         name="ordercreated",
     *         in="query",
     *         description="Ordenar fecha de Creación del producto",
     *         required=false,
     *         @OA\Schema(type="string", example="ASC")
     *     ),
     *      *     @OA\Parameter(
     *         name="is_active",
     *         in="query",
     *         description="Filtrar por estado del producto",
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
            $productos = Producto::name($request->name)
                ->orderCreatedBy($request->ordercreated)
                ->active($request->is_active)
                ->paginate(10);
            return ProductoResource::collection($productos);

        }catch(ModelNotFoundException $e){
            Handler::error();
        }
     }

        /**
     * @OA\Post(
     *     tags={"Productos"},
     *     path="/api/producto",
     *     description="Crear un producto nuevo",
     *     security={{"token": {}}},
     *     operationId="producto_store",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"name",},
     *                  @OA\Property(property="name", type="string", description="Nombre del producto", example="Laptop"),
        *                  @OA\Property(property="description", type="string", description="Descripción del producto", example="Laptop de alta gama"),
        *                  @OA\Property(property="price", type="number", format="float", description="Precio del producto", example="1500.00"),
        *                  @OA\Property(property="divisa_id", type="integer", description="ID de la divisa", example="1"),
        *                  @OA\Property(property="tax_cost", type="number", format="float", description="Costo del impuesto", example="150.00"),
        *                  @OA\Property(property="manufacturing_cost", type="number", format="float", description="Costo de fabricación", example="1000.00"),
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
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'divisa_id' => 'required|exists:divisas,id',
            'tax_cost' => 'nullable|numeric|min:0',
            'manufacturing_cost' => 'nullable|numeric|min:0',

        ]);
        try {
            $producto = new Producto();
            $producto->name = $request->name;
            $producto->description = $request->description;
            $producto->price = $request->price;
            $producto->divisa_id = $request->divisa_id;
            $producto->tax_cost = $request->tax_cost ?? 0.00;
            $producto->manufacturing_cost = $request->manufacturing_cost ?? 0.00;
            $producto->save();

            return new ProductoResource($producto);
        } catch (ValidationException $e) {
            Handler::errorCreacion();
        }
    }

    /**
     * @OA\Get(
     *     tags={"Productos"},
     *     path="/api/producto/{producto}/show",
     *     description="Obtener un producto por ID",
     *     security={{"token": {}}},
     *     operationId="producto_show",
     * @OA\Parameter(
     *         name="producto",
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
    public function show(Producto $producto)
    {
        try {
            return new ProductoFullResource($producto);
        } catch (ModelNotFoundException $e) {
             return $this->errorResponse('Recurso no encontrado', ResponseCodes::NOT_FOUND, ['error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Productos"},
     *     path="/api/producto/{producto}/update",
     *     description="Actualizar un producto",
     *     security={{"token": {}}},
     *     operationId="producto_update",
     * @OA\Parameter(
     *         name="producto",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"name",},
     *                  @OA\Property(property="name", type="string", description="Nombre de la divisa", example="dolar"),
        *                  @OA\Property(property="description", type="string", description="Descripción del producto", example="Laptop de alta gama"),
        *                  @OA\Property(property="price", type="number", format="float", description="Precio del producto", example="1500.00"),
        *                  @OA\Property(property="divisa_id", type="integer", description="ID de la divisa", example="1"),
        *                  @OA\Property(property="tax_cost", type="number", format="float", description="Costo del impuesto", example="150.00"),
        *                  @OA\Property(property="manufacturing_cost", type="number", format="float", description="Costo de fabricación", example="1000.00"),
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

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'divisa_id' => 'required|exists:divisas,id',
            'tax_cost' => 'nullable|numeric|min:0',
            'manufacturing_cost' => 'nullable|numeric|min:0',
        ]);

        try {
            $producto->name = $request->name;
            $producto->description = $request->description;
            $producto->price = $request->price;
            $producto->divisa_id = $request->divisa_id;
            $producto->tax_cost = $request->tax_cost ?? 0.00;
            $producto->manufacturing_cost = $request->manufacturing_cost ?? 0.00;
            $producto->save();

            return new ProductoResource($producto);
        } catch (ModelNotFoundException $e) {
             return Handler::errorCreacion('Error al actualizar el recurso.'.$e ->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Productos"},
     *     path="/api/producto/{producto}/delete",
     *     description="Eliminar un producto",
     *     security={{"token": {}}},
     *     operationId="producto_destroy",
     * @OA\Parameter(
     *         name="producto",
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
    public function destroy(Producto $producto)
    {
        try {
            if ($producto->is_active) {
                return $this->errorResponse('No se puede eliminar un producto activo', ResponseCodes::UNPROCESSABLE_ENTITY);
            }
            $producto->delete();
            return Handler::successDelete();
        } catch (\Throwable $th) {
                throw new SomethingWentWrong($th);
        }
    }

 /**
     * @OA\Post(
     *     tags={"Productos"},
     *     path="/api/producto/{producto}/toggle",
     *     description="Activar o desactivar un producto",
     *     security={{"token": {}}},
     *     operationId="producto_toggle",
     * @OA\Parameter(
     *          name="producto",
     *          in="path",
     *          description="producto Id",
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
    public function toggle(Producto $producto)
    {

        try {
            $producto->is_active = !$producto->is_active;
            $producto->save();
            return new ProductoResource($producto);
        } catch (NotFoundException $e) {
           return $this->errorResponse('Recurso no encontrado', ResponseCodes::NOT_FOUND, ['error' => $e->getMessage()]);
        }
    }
}
