<?php

namespace Src\App\Models;

use Src\Models\Model;

class Vehiculo extends Model
{
    public static function _table(): string
    {
        return 'Vehiculos';
    }

    public static function _primary(): string
    {
        return 'vehiculo_id';
    }
}
