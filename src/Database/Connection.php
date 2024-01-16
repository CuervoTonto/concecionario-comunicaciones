<?php

namespace Src\Database;

use PDO;
use Closure;
use LogicException;
use PDOStatement;

class Connection
{
    /**
     * pdo instance
     */
    private Closure|PDO $pdo;

    /**
     * build a instance of Connection
     * 
     * @param Closure<>:PDO $pdo function that return a pdo instance
     */
    public function __construct(Closure $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * obtains the pdo instance
     * 
     * @return PDO pdo connection instance
     */
    public function pdo(): PDO
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        if (! ($pdo = call_user_func($this->pdo)) instanceof PDO) {
            throw new LogicException('pdo closure must return a instance of [PDO]');
        }

        return $this->pdo = $pdo;
    }

    /**
     * execute query to obtains the registers from the database
     * 
     * @param string $statement query to execute
     * @param array<string, mixed> $bindings values to bind on statement
     * 
     * @return array the items
     */
    public function select(string $statement, array $bindings = []): array
    {
        $stmt = $this->pdo()->prepare($statement);
        $this->bindValues($stmt, $bindings);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * execute query to create a register on database
     * 
     * @param string $statement query to execute
     * @param array<string, mixed> $bindings values to bind on statement
     * 
     * @return bool was created sucessfully?
     */
    public function create(string $statement, array $bindings = []): bool
    {
        return $this->statement($statement, $bindings);
    }

    /**
     * execute query to update a registers on database
     * 
     * @param string $statement query to execute
     * @param array<string, mixed> $bindings values to bind on statement
     * 
     * @return int updated rows
     */
    public function update(string $statement, array $bindings = []): int
    {
        return $this->statementNumRows($statement, $bindings);
    }

    /**
     * execute query to delete registers on database
     * 
     * @param string $statement query to execute
     * @param array<string, mixed> $bindings values to bind on statement
     * 
     * @return int deleted rows 
     */
    public function delete(string $statement, array $bindings = []): int
    {
        return $this->statementNumRows($statement, $bindings);
    }
    
    /**
     * execute statement
     * 
     * @param string $statement query to execute
     * @param array<string, mixed> $bindings values to bind on statement
     * 
     * @return bool execution result
     */
    public function statement(string $statement, array $bindings): bool
    {
        $stmt = $this->pdo()->prepare($statement);
        $this->bindValues($stmt, $bindings);

        return $stmt->execute();
    }

    /**
     * execute statement
     * 
     * @param string $statement query to execute
     * @param array<string, mixed> $bindings values to bind on statement
     * 
     * @return int the rows affected by statement
     */
    public function statementNumRows(string $statement, array $bindings): int
    {
        $stmt = $this->pdo()->prepare($statement);
        $this->bindValues($stmt, $bindings);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * set the binding values on statement
     * 
     * @param PDOStatement $stmt statement to bind
     * @param array<string, mixed> $bindings the values to use for binding
     * 
     * @return void
     */
    private function bindValues(PDOStatement $stmt, array $bindings = []): void
    {
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key + 1, $value, match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_resource($value) => PDO::PARAM_LOB,
                default => PDO::PARAM_STR,
            });
        }
    }
}