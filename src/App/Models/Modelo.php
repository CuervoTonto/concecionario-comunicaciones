<?php

namespace Src\App\Models;

use Src\Models\Model;

class Modelo extends Model
{
    public static function _table(): string
    {
        return 'Modelos';
    }

    public static function _primary(): string
    {
        return 'modelo_id';
    }
}
