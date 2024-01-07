<?php

namespace Src\Http\Components;

use Src\Contracts\Http\Cookie as Contract;

class Cookie implements Contract 
{
    /**
     * cookie name
     */
    private string $name;

    /**
     * cookie value
     */
    private string $value;

    /**
     * build a instance of Cookie
     */
    public function __construct(string $name, string $value = '')
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * obtains the cookie name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * obtains the cookie value
     */
    public function getValue(): string
    {
        return $this->value;
    }
}