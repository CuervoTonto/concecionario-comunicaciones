<?php

namespace Src\Console\Input;

class InputDefinition
{
    /**
     * definitions to arguments
     * 
     * @var array<InputArgument>
     */
    private array $arguments;

    /**
     * definitions to long options
     */
    private array $longs;

    /**
     * definitions to short options
     */
    private array $shorts;

    /**
     * build a instance of InputDefinition
     * 
     * @param array<InputArgument> $arguments definitions to arguments
     * @param array<InputLongOption> $longOptions definitions to long options
     * @param array<InputShortOption> $shortOptions definitions to short options
     */
    public function __construct(
        array $arguments = [],
        array $longOptions = [],
        array $shortOptions = [],
    ) {
        $this->arguments = $arguments;
        $this->longs = $longOptions;
        $this->shorts = $shortOptions;
    }

    /**
     * add a argument to the definition of arguments
     * 
     * @param InputArgument $arg argument to add
     * 
     * @return void
     */
    public function addArgument(InputArgument $arg): void
    {
        $this->arguments[] = $arg;
    } 

    /**
     * add a option to the definition of long options
     * 
     * @param InputLongOption $opt long option to add
     * 
     * @return void
     */
    public function addLongOption(InputLongOption $opt): void
    {
        $this->longs[] = $opt;
    } 

    /**
     * add a option to the definition of short options
     * 
     * @param InputShortOption $opt long option to add
     * 
     * @return void
     */
    public function addShortOption(InputShortOption $opt): void
    {
        $this->shorts[] = $opt;
    } 

    /**
     * obtains the definitions for arguments
     * 
     * @return array<InputArgument>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * obtains the definitions for long options
     */
    public function getLongOptions(): array
    {
        return $this->longs;
    }

    /**
     * obtains the definitions for short options
     */
    public function getShortOptions(): array
    {
        return $this->shorts;
    }
}