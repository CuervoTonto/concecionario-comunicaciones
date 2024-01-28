<?php

namespace Src\Console\Commands;

use Src\Classes\Globals;

class ListCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $commands = Globals::get('console')->commands();
        $output = [];

        foreach ($commands as $commandName => $command) {
            $output[] = sprintf(
                '* %s: %s',
                $commandName,
                call_user_func([$command, 'description'])
            );
        }

        echo PHP_EOL, implode(PHP_EOL, $output), PHP_EOL, PHP_EOL;
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Show the avaiable command';
    }
}