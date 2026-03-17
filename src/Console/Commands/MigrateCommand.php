<?php

namespace Src\Console\Commands;

use PDO;
use Src\Console\Input\InputShortOption;
use Src\Console\Input\InputTokenMode;
use Src\Support\Configuration;

class MigrateCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    public function execute(): void
    {
        $withSeed = $this->input->shortOption('s');

        $config = require fromBase('config/database.php');

        $pdo = $this->connect($config);

        $this->runFile($pdo, fromBase('resources/db/V1__tables.sql'), 'Estructura de tablas');

        if ($withSeed) {
            $this->runFile($pdo, fromBase('resources/db/V2__seed_data.sql'), 'Datos de prueba');
        }

        echo PHP_EOL;
    }

    /**
     * Create a PDO connection from config
     */
    private function connect(Configuration $config): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $config->get('host'),
            $config->get('port'),
            $config->get('dbname'),
        );

        echo PHP_EOL . "* Conectando a la base de datos [{$config->get('dbname')}]... ";

        $pdo = new PDO($dsn, $config->get('user'), $config->get('password'), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        echo "OK" . PHP_EOL;

        return $pdo;
    }

    /**
     * Execute all statements from a .sql file
     */
    private function runFile(PDO $pdo, string $file, string $label): void
    {
        if (!file_exists($file)) {
            echo "* [{$label}] Archivo no encontrado: {$file}" . PHP_EOL;
            return;
        }

        echo "* Ejecutando [{$label}]... ";

        $sql = file_get_contents($file);

        // Strip comments and split by semicolon
        $sql = preg_replace('/--[^\n]*\n/', "\n", $sql);
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn($s) => $s !== ''
        );

        foreach ($statements as $statement) {
            $pdo->exec($statement);
        }

        echo "OK (" . count($statements) . " sentencias)" . PHP_EOL;
    }

    /**
     * {@inheritDoc}
     */
    public static function definitionShortOptions(): array
    {
        return [
            new InputShortOption('s', InputTokenMode::NO_VALUE),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function description(): string
    {
        return 'Crea las tablas de la BD. Usa -s para incluir datos de prueba (seed)';
    }
}
