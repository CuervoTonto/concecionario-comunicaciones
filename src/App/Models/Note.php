<?php

namespace App\Models;

use Src\Models\Model;

class Note extends Model
{
    public static function _table(): string
    {
        return 'notes';
    }
}