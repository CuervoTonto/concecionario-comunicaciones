<?php

namespace Src\Database\Query\Clauses\Joins;

use RuntimeException;
use Src\Database\Query\Builder;
use Src\Database\Query\Components\Expression;
use Src\Database\Query\Components\ExpressionRaw;
use Src\Database\Query\Grammar;

class JoinClause
{
    /**
     * valid types for join
     * 
     * @var array<string>
     */
    protected $validTypes = [
        'inner',
        'left',
        'right',
    ];

    /**
     * union type
     */
    private string $type;

    /**
     * table to join
     */
    private string $from;

    /**
     * first value for join acoplament
     */
    private null|Expression $first;

    /**
     * second value for join acoplament
     */
    private null|Expression $second;

    /**
     * operation of acoplament
     */
    private null|Expression $operation;

    /**
     * build a instance of JoinClause
     * 
     * @param string $type the type of join
     */
    public function __construct(string $from, string $type)
    {
        $this->from = $from;
        $this->setType($type);
    }

    /**
     * create a instance of JoinClause to join a table
     * 
     * @param string $table the table for join
     * @param string $type  type of join
     * 
     * return static
     */
    public static function fromTable(string $table, string $type): void
    {
        // 
    }

    /**
     * create a instance of JoinClause to join a subquery
     * 
     * @param Builder $sub the subquery for join
     * @param string $as the alias for subquery
     * @param string $type type of join
     * 
     * return static
     */
    public static function fromSub(Builder $sub, string $as, string $type): void
    {
        // 
    }

    /**
     * set the type of the clause
     * 
     * @param string $type the type of join
     * 
     * @return $this the current instance
     * 
     * @throws RuntimeException on case of invalid type
     */
    private function setType(string $type): static
    {
        if (! in_array($type, $this->validTypes)) {
            throw new RuntimeException("invalid join type [{$type}]");
        }

        $this->type = $type;

        return $this;
    }

    /**
     * add on clause to join
     * 
     * @param string $first first column
     * @param string $operation operation sign
     * @param string $second second column
     * 
     * @return $this
     */
    public function on(string $first, string $operation, string $second = null): static
    {
        if (is_null($second)) {
            [$second, $operation] = [$operation, '='];
        }

        $this->first = Expression::new($first);
        $this->operation = ExpressionRaw::new($operation);
        $this->second = Expression::new($second);

        return $this;
    }

    /**
     * obtains the type of join
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * obtains the first column to ON specification
     * 
     * @return Expression|false the first column or false if no defined
     */
    public function first(): Expression|false
    {
        return $this->first ?? false;
    }

    /**
     * obtains the second column to ON specification
     * 
     * @return Expression|false the second column or false if no defined
     */
    public function second(): Expression|false
    {
        return $this->second ?? false;
    }

    /**
     * obtains the operation sign of ON specification
     * 
     * @return Expression|false the operation sign or false if no defined
     */
    public function operation(): Expression|false
    {
        return $this->operation ?? false;
    }

    public function isSub(): bool
    {
        return str_starts_with($this->from, '(SELECT');
    }
}