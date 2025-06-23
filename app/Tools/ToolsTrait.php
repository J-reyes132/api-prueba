<?php


namespace App\Tools;

use App\Models\Convocatoria;
use App\Models\ConvocatoriaItem;
use App\Models\CreditoConvocatorias;
use App\Models\TipoCaso;
use App\Models\User;
use Illuminate\Support\Str;

trait ToolsTrait
{

    public static function deleted()
    {
        return response()->json(['status' => 'successful', 'message' => 'Recurso borrado'], ResponseCodes::ACCEPTED);
    }


    public function getCasoCodigo(TipoCaso $tipoCaso, $id, $fecha)
    {

        return strtoupper($tipoCaso->prefijo . $fecha . str_pad($id, 5, '0', STR_PAD_LEFT));
    }

    public function getCasoCodigos($prefijo, $ultimo_id, $today)
    {
        $codigo = $prefijo . $today . str_pad($ultimo_id, 14 - strlen($prefijo . $today), '0', STR_PAD_LEFT);
        return $codigo;
    }


}
