<?php

namespace Src\Console\Input;

enum InputTokenMode: string
{
    /**
     * input token "required" type
     */
    case REQUIRED = 'Required';

    /**
     * input token "optional" type
     */
    case OPTIONAL = 'Optional';

    /**
     * input token no use values
     */
    case NO_VALUE = 'NoValue';
}