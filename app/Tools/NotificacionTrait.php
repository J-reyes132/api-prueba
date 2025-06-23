<?php

namespace App\Tools;

use App\Models\Notificacion;
use App\Models\User;




trait NotificacionTrait
{
    public function notificar($user_id, $mensaje)
    {

        $notificacion = new Notificacion();
        $notificacion->user_id = $user_id;
        $notificacion->notificacion = $mensaje;
        $notificacion->save();
        return $notificacion;

    }

    public function notificarRegistro($user){


        $notificacion = new Notificacion();
        $notificacion->user_id = $user->id;
        $notificacion->notificacion = "Bienvenido a la plataforma de la Universidad de El Salvador";
        $notificacion->save();
        return $notificacion;
    }

}
