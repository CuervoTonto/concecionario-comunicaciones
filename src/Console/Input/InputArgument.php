<?php

namespace Src\Console\Input;

class InputArgument extends AbstractInputToken
{
    /**
     * {@inheritDoc}
     */
    protected array $validModes = [
        InputTokenMode::REQUIRED,
        InputTokenMode::OPTIONAL,
    ];
}