<?php

namespace Src\Database\Query;

use RuntimeException;
use Src\Database\Connection;
use Src\Database\Query\Clauses\Joins\JoinClause;
use Src\Database\Query\Clauses\Wheres\Where;
use Src\Database\Query\Clauses\Wheres\WhereBasic;
use Src\Database\Query\Clauses\Wheres\WhereNested;
use Src\Database\Query\Components\Expression;
use Src\Database\Query\Components\ExpressionRaw;

class Builder
{
    /**
     * connection instance
     */
    private Connection $connection;

    /**
     * target table registers (table or subquery)
     */
    private Expression $from;
    
    /**
     * target columns
     */
    private array $columns = [];

    /**
     * where clauses
     */
    private array $wheres = [];

    /**
     * join clauses
     */
    private array $joins = [];

    /**
     * order clause
     */
    private ?array $order = null;

    /**
     * group by clause
     */
    private ?array $group = null;

    /**
     * limit clause
     */
    private ?int $limit = null;

    /**
     * list of bindings
     * 
     * @var array<string, array<mixed>>
     */
    private array $bindings = [
        'from' => [],
        'join' => [],
        'where' => [],
    ];

    /**
     * build a instance of Builder
     * 
     * @param string $table target table
     * @param Connection $conn connection instance
     */
    public function __construct(Connection $conn)
    {
        $this->connection = $conn;
    }

    /**
     * set the from clause
     * 
     * @param Builder|string $from the table or subquery (Builder)
     * @param string $as the name of the subquery
     * 
     * @return $this
     */
    public function from(Builder|string $from, string $as = null): static
    {
        return is_string($from) ? $this->fromTable($from) : $this->fromSub($from, $as);
    }

    /**
     * set the from clause of a table to query
     * 
     * @param string $table
     * 
     * @return void
     */
    public function fromTable(string $table): static
    {
        if (str_contains($table, ' ')) {
            $table = substr($table, 0, strpos($table, ' '));
        }

        $table = Grammar::clearToInjection($table);
        $this->from = Expression::new($table);

        return $this;
    }

    /**
     * set from clause of sub query to query
     * 
     * @param Builder $from builder of subquery
     * @param string $as alias of subquery
     * 
     * @return $this
     */
    public function fromSub(Builder $from, string $as): static
    {
        $as = Grammar::clearToInjection($as);
        $query = Grammar::select($from);

        $this->from = ExpressionRaw::new("({$query})", $as) ;
        $this->setBinding('from', $from->getBindings());

        return $this;
    }

    /**
     * performance a insert query to db
     */
    public function insert(array $data): bool
    {
        return $this->connection->create(
            Grammar::insert($this, array_keys($data)),
            array_values($data),
        );
    }

    /**
     * performance a delete query to db
     */
    public function delete(): bool
    {
        return $this->connection->delete(
            Grammar::delete($this),
            $this->getBindings(),
        );
    }

    /**
     * performance a update query to db
     */
    public function update(array $data): bool
    {
        return $this->connection->update(
            Grammar::update($this, array_keys($data)),
            array_merge(array_values($data), $this->getBindings()),
        );
    }

    /**
     * performance a "select" query to db
     */
    public function get(): array
    {
        return $this->connection->select(
            Grammar::select($this),
            $this->getBindings()
        );
    }

    /**
     * add where clause
     * 
     * @param string $column the column name
     * @param mixed $operation the condition operation
     * @param mixed $value the value for condition
     * @param string $boolean concatenate boolean
     * 
     * @return $this
     */
    public function where(
        string|array $column,
        mixed $operation = null,
        mixed $value = null,
        string $boolean = 'and',
    ): static {
        if (is_array($column)) {
            $where = new WhereNested($column, $boolean);
        } else {
            $where = new WhereBasic($column, $operation, $value, $boolean);
        }

        $this->wheres[] = $where;
        $this->addBinding('where', $where->obtainBindingsValues());

        return $this;
    }

    /**
     * add where clause for "or" boolean
     * 
     * @param string $column the column name
     * @param mixed $operation the condition operation
     * @param mixed $value the value for condition
     * 
     * @return $this
     */
    public function orWhere(
        string|array $column,
        mixed $operation = null,
        mixed $value = null,
    ): static {
        return $this->where($column, $operation, $value, 'or');
    }

    /**
     * add join clause
     * 
     * @param string $from the table to join
     * @param string $type the type of join
     * @param null|string $columnA the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $columnB the second column to join
     * 
     * @return $this
     */
    public function join(
        string $from,
        string $type,
        string $columnA = null,
        string $operation = null,
        string $columnB = null,
    ): static {
        $join = new JoinClause($from, $type);

        if (! is_null($columnA)) {
            $join->on($columnA, $operation, $columnB);
        }

        $this->joins[] = $join;

        return $this;
    }

    /**
     * add clause to join a subquery
     * 
     * @param Builder $from subquery (Builder)
     * @param string $as the alias for subquery
     * @param string $type the type of join
     * @param null|string $columnA the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $columnB the second column to join
     * 
     * @return $this
     */
    public function joinSub(
        Builder $from,
        string $as,
        string $type,
        string $columnA = null,
        string $operation = null,
        string $columnB = null,
    ): static {
        $builder = $from;
        $from = '(' . Grammar::select($from) . ') ' . Grammar::wrapTable($as);
        $join = new JoinClause($from, $type);

        if (! is_null($columnA)) {
            $join->on($columnA, $operation, $columnB);
        }

        $this->joins[] = $join;
        $this->addBinding('join', $builder->getBindings());

        return $this;
    }

    /**
     * set join clause of type Inner
     * 
     * @param Builder|string $from the table or subquery (Builder)
     * @param null|string $columnA the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $columnB the second column to join
     * 
     * @return $this
     */
    public function innerJoin(
        Builder|string $from,
        string $columnA = null,
        string $operation = null,
        string $columnB = null,
    ): static {
        return $this->join($from, 'inner', $columnA, $operation, $columnB);
    }

    /**
     * add join clause (subquery) of type Inner
     * 
     * @param Builder $from the table or subquery (Builder)
     * @param string $as the alias for subquery
     * @param null|string $columnA the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $columnB the second column to join
     * 
     * @return $this
     */
    public function innerJoinSub(
        Builder $from,
        string $as,
        string $columnA = null,
        string $operation = null,
        string $columnB = null,
    ): static {
        return $this->joinSub($from, $as, 'inner', $columnA, $operation, $columnB);
    }

    /**
     * set join clause of type Left
     * 
     * @param Builder|string $from the table or subquery (Builder)
     * @param null|string $columnA the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $columnB the second column to join
     * 
     * @return $this
     */
    public function leftJoin(
        Builder|string $from,
        string $columnA = null,
        string $operation = null,
        string $columnB = null,
    ): static {
        return $this->join($from, 'left', $columnA, $operation, $columnB);
    }

    /**
     * set join clause (subquery) of type Left
     * 
     * @param Builder $from the table or subquery (Builder)
     * @param string $as the alias for subquery
     * @param null|string $columnA the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $columnB the second column to join
     * 
     * @return $this
     */
    public function leftJoinSub(
        Builder $from,
        string $as,
        string $columnA = null,
        string $operation = null,
        string $columnB = null,
    ): static {
        return $this->joinSub($from, $as, 'left', $columnA, $operation, $columnB);
    }

    /**
     * set join clause of type Right
     * 
     * @param Builder|string $from the table or subquery (Builder)
     * @param null|string $columnA the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $columnB the second column to join
     * 
     * @return $this
     */
    public function rightJoin(
        Builder|string $from,
        string $columnA = null,
        string $operation = null,
        string $columnB = null,
    ): static {
        return $this->join($from, 'right', $columnA, $operation, $columnB);
    }

    /**
     * set join clause (subquery) of type Right
     * 
     * @param Builder $from the table or subquery (Builder)
     * @param string $as the alias for subquery
     * @param null|string $columnA the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $columnB the second column to join
     * 
     * @return $this
     */
    public function rightJoinSub(
        Builder $from,
        string $as,
        string $columnA = null,
        string $operation = null,
        string $columnB = null,
    ): static {
        return $this->joinSub($from, $as, 'right', $columnA, $operation, $columnB);
    }

    /**
     * set the query selected columns
     * 
     * @param string|Expression ...$columns the new columns
     * 
     * @return $this
     */
    public function select(string|Expression ...$columns): static
    {
        foreach ($columns as &$c) {
            $c = is_string($c) ? $this->columnToExpression($c) : $c;
        }

        $this->columns = $columns;

        return $this;
    }

    /**
     * add a column to query selected columns
     * 
     * @param string $column column to add
     * @param string $as the alias for column
     * 
     * @return $this
     */
    public function addColumn(string|Expression $column, string $as = null): static
    {
        if (is_string($column)) {
            $column = Expression::new($column, $as);
        }

        $this->columns[] = $column;

        return $this;
    }

    /**
     * transform a string of column to a expression
     * 
     * @param string $column the column string
     * 
     * @return Expression
     */
    protected function columnToExpression(string $column): Expression
    {
        return Expression::new(
            ($tokens = explode(' as ', $column))[0],
            $tokens[1] ?? null
        );
    }

    /**
     * set the limit for query register fetched/affecteds
     * 
     * @param null|int $amount the limit of registers
     * 
     * @return $this
     */
    public function limit(?int $amount): static
    {
        if (is_null($amount) || $amount >= 0) {
            $this->limit = $amount;
        }

        return $this;
    }

    /**
     * set order by clause
     * 
     * @param string $column the referenced column
     * @param string $direction the order directio (asc|desc)
     * 
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        if (! in_array($direction, ['asc', 'desc'])) {
            throw new RuntimeException('invalid order by direction');
        }

        $this->order = [
            Grammar::clearToInjection($column),
            $direction,
        ];

        return $this;
    }

    /**
     * add group by clause to query
     * 
     * @param string ...$columns the columns to agroup
     * 
     * @return $this
     */
    public function groupBy(string ...$columns): static
    {
        foreach ($columns as &$c) {
            $c = Expression::new($c);
        }

        $this->group = $columns;

        return $this;
    }

    /**
     * add a binding to builder
     * 
     * @param string $to the clause of the bindings
     * @param array $bindings values to use on binding
     * 
     * @return void
     */
    private function addBinding(string $to, array $bindings): void
    {
        if (! array_key_exists($to, $this->bindings)) {
            throw new RuntimeException('invalid clause to binding');
        }

        $this->bindings[$to] = array_merge(
            $this->bindings[$to],
            $bindings,
        );
    }

    /**
     * set the bindings to query
     * 
     * @param string $to the clause of the bindings
     * @param array $bindings new values to use on binding
     * 
     * @return void
     */
    private function setBinding(string $to, array $bindings): void
    {
        if (! array_key_exists($to, $this->bindings)) {
            throw new RuntimeException('invalid clause to binding');
        }

        $this->bindings[$to] = $bindings;
    }

    /**
     * get the values to use on binding
     * 
     * @return array<mixed>
     */
    public function getBindings(): array
    {
        return array_merge(
            ...array_values($this->bindings)
        );
    }

    /**
     * obtains the colums
     * 
     * @return array<string>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * get the where clauses
     * 
     * @return array<Where>
     */
    public function getWheres(): array
    {
        return $this->wheres;
    }

    /**
     * get the order by clause
     * 
     * @return array<string, string>
     */
    public function getOrderBy(): null|array
    {
        return $this->order;
    }

    /**
     * get the order by clause
     * 
     * @return null|array<Expression>
     */
    public function getGroupBy(): null|array
    {
        return $this->group;
    }

    /**
     * get the limit of registers to handle on query
     */
    public function getLimit(): null|int
    {
        return $this->limit;
    }

    /**
     * obtains the joins clauses
     * 
     * @return array<JoinClause>
     */
    public function getJoins(): array
    {
        return $this->joins;
    }

    /**
     * get the from 
     */
    public function getFrom(): Expression
    {
        return $this->from;
    }

    /**
     * obtains the connection instance
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }
}