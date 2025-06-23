<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;

class EmailNotValid extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'Email no es valido'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}
