<?php


namespace App\Tools;

use Illuminate\Support\Facades\DB;

trait ValidadorTrait
{

public function get_count($tabla, $validacion, $valor){
     $count = DB::table($tabla)->where($validacion, '=', $valor)->count();

     return $count;
}

public function get_sum($tabla, $validacion, $valor, $suma){
    $sum = DB::table($tabla)->where($validacion, '=', $valor)->sum($suma);

    return $sum;
}

public function notEmptySocioEconomico($usuario){
    $socio_economico = DB::table('socio_economicos')->where('user_id', '=', $usuario)->whereNotNull('padre_nombre')->count();

    return $socio_economico;
}
}
