<?php

namespace Src\Console\Input;

class InputShortOption extends AbstractInputToken
{
    /**
     * {@inheritDoc}
     */
    protected array $validModes = [
        InputTokenMode::OPTIONAL,
        InputTokenMode::NO_VALUE,
    ];
}