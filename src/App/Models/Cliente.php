<?php

namespace Src\App\Models;

use Src\Models\Model;

class Cliente extends Model
{
    public static function _table(): string
    {
        return 'Clientes';
    }

    public static function _primary(): string
    {
        return 'cliente_id';
    }
}
