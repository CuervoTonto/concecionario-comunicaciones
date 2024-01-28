<?php

namespace Src\Console;

use RuntimeException;
use Src\Console\Commands\BaseCommand;
use Src\Console\Input\Input;
use Src\Console\Input\InputArgument;
use Src\Console\Input\InputLongOption;
use Src\Console\Input\InputShortOption;
use Src\Console\Input\InputBind;
use Src\Console\Input\InputDefinition;
use Src\Console\Input\InputTokenMode as InpMode;

class Console
{
    /**
     * list of commands
     * 
     * @var array<string, string>
     */
    protected $commands = [
        'server' => \Src\Console\Commands\ServerCommand::class,
        'list' => \Src\Console\Commands\ListCommand::class,
    ];

    /**
     * default command (no necesary would be on list)
     */
    protected $default = 'server';

    /**
     * handle a input
     * 
     * @param Input $input the input instance
     */
    public function handle(Input $input): mixed
    {
        $input = new InputBind($input, $this->baseDefinition());
        $command = $input->firstArgument();

        $this->validateRegisteredCommand($command);
        $command = $this->commands[$command] ?? $command;
        $this->validateIsCommandInstance($command);

        $input->setDefinition($this->makeCommandDefintion(
            call_user_func([$command, 'definitionArguments']),
            call_user_func([$command, 'definitionLongOptions']),
            call_user_func([$command, 'definitionShortOptions']),
        ));

        return (new $command($input))->execute();
    }

    /**
     * validate the token is a valid command
     */
    public function validateRegisteredCommand(string $command): void
    {
        if (
            $command !== $this->default
            && ! array_key_exists($command, $this->commands)
        ) {
            throw new RuntimeException(
                "Call to unregistered console command [{$command}]"
            );
        }
    }

    /**
     * validate the token is a valid command
     */
    public function validateIsCommandInstance(string $command): void
    {
        if (! is_a($command, BaseCommand::class, true)) {
            throw new RuntimeException("[{$command}] is not a Command instance");
        }
    }

    /**
     * create a base InputDefintion
     * 
     * @return InputDefinition base definition
     */
    protected function baseDefinition(): InputDefinition
    {
        return new InputDefinition([
            new InputArgument('command', InpMode::OPTIONAL, $this->default),
        ]);
    }

    /**
     * create a input definition for a command
     * 
     * @param array<InputArgument> $arguments command's arguments definition
     * @param array<InputLongOption> $longOptions command's long options definition
     * @param array<InputShortOption> $shortOptions command's short options definitionQ
     * 
     * @return InputDefinition definition to command
     */
    protected function makeCommandDefintion(
        array $arguments,
        array $longOptions,
        array $shortOptions
    ): InputDefinition {
        $arguments = array_merge([
            new InputArgument('command', InpMode::OPTIONAL, $this->default),
        ], $arguments);

        return new InputDefinition($arguments, $longOptions, $shortOptions);
    }

    /**
     * obtains the console commands
     */
    public function commands(): array
    {
        return $this->commands;
    }
}