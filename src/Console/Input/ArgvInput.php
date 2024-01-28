<?php

namespace Src\Console\Input;

class ArgvInput extends Input
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct(
            array_slice($_SERVER['argv'], 1)
        );
    }
}