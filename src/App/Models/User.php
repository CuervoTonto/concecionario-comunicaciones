<?php 

namespace Src\App\Models;

use Src\Models\Model;

class User extends Model
{
    /**
     * {@inheritDoc}
     */
    public static function _table(): string
    {
        return 'users';
    }

    /**
     * modify name attribute when obtein it 
     */
    protected static function usernameGetter(string $name): string
    {
        return 'User:: ' . $name;
    }

    /**
     * modify name attribute when obtein it 
     */
    protected static function nameGetter(string $name): string
    {
        return ucwords($name);
    }

    /**
     * modify last name attribute when create
     */
    protected static function lastnameGetter(string $lastname): string
    {
        return ucwords($lastname);
    }
}