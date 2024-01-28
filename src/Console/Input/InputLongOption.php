<?php

namespace Src\Console\Input;

class InputLongOption extends AbstractInputToken
{
    /**
     * {@inheritDoc}
     */
    protected array $validModes = [
        InputTokenMode::OPTIONAL,
        InputTokenMode::NO_VALUE,
    ];
}