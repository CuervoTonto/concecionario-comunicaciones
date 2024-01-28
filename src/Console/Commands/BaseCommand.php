<?php

namespace Src\Console\Commands;
use RuntimeException;
use Src\Console\Input\InputBind;

abstract class BaseCommand
{
    /**
     * input instance
     */
    protected InputBind $input;

    /**
     * performance command action
     */
    abstract public function execute();

    /**
     * build a BaseCommand
     * 
     * @param InputBind the inputs for command
     */
    public function __construct(InputBind $input)
    {
        $this->input = $input;
    }

    /**
     * @return array<\Src\Console\Input\InputArgument>
     */
    public static function definitionArguments(): array
    {
        return [];
    }

    /**
     * @return array<\Src\Console\Input\InputArgument>
     */
    public static function definitionLongOptions(): array
    {
        return [];
    }

    /**
     * @return array<\Src\Console\Input\InputArgument>
     */
    public static function definitionShortOptions(): array
    {
        return [];
    }

    /**
     * obtains the desceiption of command
     * 
     * @return string
     */
    public static function description(): string
    {
        return 'N/A';
    }
}