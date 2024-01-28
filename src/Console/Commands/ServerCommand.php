<?php

namespace Src\Console\Commands;

use Src\Console\Input\InputArgument;
use Src\Console\Input\InputLongOption;
use Src\Console\Input\InputTokenMode;

class ServerCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $cmd = sprintf(
            'php -S %s:%s -t %s',
            $this->input->longOption('host'),
            $this->input->longOption('port'),
            $this->input->longOption('root'),
        );

        exec($cmd);
    }

    /**
     * {@inheritDoc}
     */
    public static function definitionLongOptions(): array
    {
        return [
            new InputLongOption('host', InputTokenMode::OPTIONAL, '127.0.0.1'),
            new InputLongOption('port', InputTokenMode::OPTIONAL, '3000'),
            new InputLongOption('root', InputTokenMode::OPTIONAL, 'public'),
        ];
    }
}