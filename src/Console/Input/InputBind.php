<?php

namespace Src\Console\Input;
use RuntimeException;

class InputBind
{
    /**
     * input instance
     */
    private Input $input;

    /**
     * definition to input
     */
    private InputDefinition $definition;

    /**
     * binded arguments
     * 
     * @var array<string, string>
     */
    private array $arguments;

    /**
     * binded long options
     * 
     * @var array<string, string>
     */
    private array $longOptions;

    /**
     * binded short options
     * 
     * @var array<string, bool>
     */
    private array $shortOptions;

    /**
     * build a instance of InputBind
     * 
     * @param Input $input input instance
     * @param InputDefinition $definition the definition to bind the input
     */
    public function __construct(Input $input, InputDefinition $definition)
    {
        $this->input = $input;
        $this->definition = $definition;
        $this->bind();
    }

    /**
     * set the definition to bind the input
     */
    public function setDefinition(InputDefinition $definition): void
    {
        $this->definition = $definition;
        $this->bind();
    }

    /**
     * bind input with definition
     * 
     * @return void
     */
    protected function bind(): void
    {
        $this->bindAllArguments();
        $this->bindAllLongOptions();
        $this->bindAllShortOptions();
    }

    /**
     * bind the arguments of input
     * 
     * @return void
     */
    protected function bindAllArguments(): void
    {
        $this->arguments = [];

        foreach ($this->definition->getArguments() as $key => $arg) {
            $this->bindArgument($arg, $this->input->argument($key));
        }
    }

    /**
     * bind the long options of input
     * 
     * @return void
     */
    protected function bindAllLongOptions(): void
    {
        $this->longOptions = [];

        foreach ($this->definition->getLongOptions() as $opt) {
            $this->bindLongOption($opt, $this->input->longOption($opt->name()));
        }
    }

    /**
     * bind the short options of input
     * 
     * @return void
     */
    protected function bindAllShortOptions(): void
    {
        $this->shortOptions = [];

        foreach ($this->definition->getShortOptions() as $opt) {
            $this->bindShortOption($opt, $this->input->ShortOption($opt->name()));
        }
    }

    /**
     * bind a token to argument
     * 
     * @param InputArgument $arg argument definition
     * @param null|string $token token to bind
     * 
     * @return void
     */
    protected function bindArgument(InputArgument $arg, null|string $token): void
    {
        if ($arg->isRequired() && is_null($token)) {
            $message = "Input need the argument [{$arg->name()}]";
        } elseif ($arg->isOptional() && is_null($token) && is_null($arg->value())) {
            $message = "No custom or default value given for optional argument {$arg->name()}";
        }

        if (isset($message)) {
            throw new RuntimeException($message);
        }

        $this->arguments[$arg->name()] = $token ?? $arg->value();
    }

    /**
     * bind a token to long option
     * 
     * @param InputLongOption $opt long option definition
     * @param null|string $token token to bind
     * 
     * @return void
     */
    protected function bindLongOption(InputLongOption $opt, null|string $token): void
    {
        if ($opt->needValue() && is_null($token) && is_null($opt->value())) {
            $message = "No custom or default value given for long option {$opt->name()}";
        }

        if (isset($message)) {
            throw new RuntimeException($message);
        }

        $this->longOptions[$opt->name()] = $opt->needValue()
            ? $token ?? $opt->value()
            : $token !== null;
    }

    /**
     * bind a token to short option
     * 
     * @param InputShortOption $opt long option definition
     * @param null|string $token token to bind
     * 
     * @return void
     */
    protected function bindShortOption(InputShortOption $opt, string|null $token): void
    {
        $this->shortOptions[$opt->name()] = $token !== null;
    }

    /**
     * obtains the first argument from binded
     * 
     * @return string
     */
    public function firstArgument(): string
    {
        return reset($this->arguments);
    }

    /**
     * obtains a argument from binded
     * 
     * @param string $name argument's name
     * 
     * @return string
     */
    public function argument(string $name): string
    {
        return $this->arguments[$name];
    }


    /**
     * obtains a long option from binded
     * 
     * @param string $name long option's name
     * 
     * @return string|bool
     */
    public function longOption(string $name): string|bool
    {
        return $this->longOptions[$name];
    }

    /**
     * obtains a short option from binded
     * 
     * @param string $name short option's name
     * 
     * @return bool
     */
    public function shortOption(string $name): bool
    {
        return $this->shortOptions[$name];
    }
}