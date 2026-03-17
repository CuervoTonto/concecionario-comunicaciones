<?php

namespace Src\App\Models;

use Src\Models\Model;

class Venta extends Model
{
    public static function _table(): string
    {
        return 'Ventas';
    }

    public static function _primary(): string
    {
        return 'venta_id';
    }
}
