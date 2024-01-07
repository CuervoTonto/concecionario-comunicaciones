<?php 

namespace Src\Contracts\Http;

interface Cookie
{
    /**
     * get cookie name
     */
    public function getName(): string;

    /**
     * get cookie value
     */
    public function getValue(): string;
}