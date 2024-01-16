<?php 

namespace Src\Database\Query;

use Src\Database\Query\Clauses\Joins\JoinClause;
use Src\Database\Query\Clauses\Wheres\Where;
use Src\Database\Query\Clauses\Wheres\WhereBasic;
use Src\Database\Query\Clauses\Wheres\WhereNested;
use Src\Database\Query\Components\Expression;
use Src\Database\Query\Components\ExpressionRaw;

final class Grammar
{
    /**
     * clauses to add on "select" statement
     */
    private static array $useOnSelect = [
        'join',
        'where',
        'groupBy',
        'order',
        'limit',
    ];

    /**
     * clauses to add on "create" statement
     */
    private static array $useOnInsert = [];

    /**
     * clauses to add on "update" statement
     */
    private static array $useOnUpdate = [
        'where',
        'order',
        'limit',
    ];

    /**
     * clauses to add on "delete" statement
     */
    private static array $useOnDelete = [
        'where',
        'order',
        'limit',
    ];

    /**
     * prevents class instantiation
     */
    private function __construct() {}

    /**
     * make a statement to create a register
     */
    public static function insert(Builder $query, array $columns): string
    {
        foreach ($columns as &$c) {
            $c = is_string($c) ? Expression::new($c) : $c;
        }

        $stmt = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::wrapFrom($query->getFrom()),
            static::columnize($columns),
            implode(',', array_fill(0, count($columns), '?'))
        );

        foreach (static::$useOnInsert as $use) {
            $method = 'aggregate' . ucfirst($use) . 'Clause';
            $stmt = static::{$method}($query, $stmt);
        }

        return $stmt;
    }

    /**
     * make a statement to get registers
     */
    public static function select(Builder $query): string
    {
        $stmt = sprintf(
            'SELECT %s FROM %s',
            self::columnize($query->getColumns()),
            self::wrapFrom($query->getFrom()),
        );

        foreach (static::$useOnSelect as $use) {
            $method = 'aggregate' . ucfirst($use) . 'Clause';
            $stmt = static::{$method}($query, $stmt);
        }

        return $stmt;
    }

    /**
     * make a statement to update registers
     */
    public static function update(Builder $query, array $columns): string
    {
        foreach ($columns as &$c) {
            $c = is_string($c) ? Expression::new($c) : $c;
        }

        $assignation = implode(', ', array_map(
            fn (Expression $c) => static::wrapColumn($c) . ' = ?',
            $columns,
        ));

        $stmt = sprintf(
            'UPDATE %s SET %s',
            static::wrapFrom($query->getFrom()),
            $assignation,
        );

        foreach (static::$useOnUpdate as $use) {
            $method = 'aggregate' . ucfirst($use) . 'Clause';
            $stmt = static::{$method}($query, $stmt);
        }

        return $stmt;
    }

    /**
     * make a statement to delete registers
     */
    public static function delete(Builder $query): string
    {
        $stmt = 'DELETE FROM ' . static::wrapFrom($query->getFrom());

        foreach (static::$useOnDelete as $use) {
            $method = 'aggregate' . ucfirst($use) . 'Clause';
            $stmt = static::{$method}($query, $stmt);
        }

        return $stmt;
    }

    /**
     * aggregate the where clause to statement
     */
    private static function aggregateWhereClause(Builder $query, string $statement): string
    {
        if (empty($wheres = $query->getWheres())) {
            return $statement;
        }

        return sprintf(
            '%s WHERE %s',
            $statement,
            self::parseWheres($wheres),
        );
    }

    /**
     * aggregate the limit clause to statement
     */
    private static function aggregateLimitClause(Builder $query, string $statement): string
    {
        if (is_null($limit = $query->getLimit())) {
            return $statement;
        }

        return sprintf(
            '%s LIMIT %s',
            $statement,
            static::clearToInjection($limit),
        );
    }

    /**
     * aggregate the order clause to statement
     */
    private static function aggregateOrderClause(Builder $query, string $statement): string
    {
        if (is_null($order = $query->getOrderBy())) {
            return $statement;
        }

        return sprintf(
            '%s ORDER BY %s %s',
            $statement,
            static::wrapColumn(Expression::new($order[0])),
            static::clearToInjection(strtoupper($order[1])),
        );
    }

    /**
     * aggregate the "group by" clause to statement
     */
    private static function aggregateGroupByClause(Builder $query, string $statement): string
    {
        if (null === $columns = $query->getGroupBy()) {
            return $statement;
        }

        return sprintf(
            '%s GROUP BY %s',
            $statement,
            static::columnize($columns)
        );
    }

    /**
     * aggragate join clause to statement
     * 
     * @param Builder $query builder where the join clauses are
     * @param string $statement statement to add join clause from Builder
     * 
     * @param string statement with join clauses
     */
    private static function aggregateJoinClause(Builder $query, string $statement): string
    {
        if (empty($joins = $query->getJoins())) {
            return $statement;
        }

        foreach ($joins as $join) {
            $statement = $statement . ' ' . static::joinClause($join);
        }

        return trim($statement);
    }

    /**
     * make string of JoinClause
     * 
     * @param JoinClause $join the JoinClause instance
     * 
     * @return string string representation of join clause
     */
    protected static function joinClause(JoinClause $join): string
    {
        $from = $join->getFrom();
        $type = $join->getType();

        return sprintf(
            '%s JOIN %s ON %s %s %s',
            strtoupper(static::wrapWord($type)),
            $join->isSub() ? $from : static::wrapTable($from),
            static::wrapColumn($join->first()),
            static::wrapWord($join->operation()->value()),
            static::wrapColumn($join->second()),
        );
    }

    /**
     * parse wheres Clause
     */
    private static function parseWheres(array $wheres): string
    {
        $conditions = array_map(function (Where $where) {
            return self::callWhereParse($where);
        }, $wheres);

        return self::ltrimBoolean(implode(' ', $conditions));
    }

    /**
     * parse a WhereBasic to a valid condition on statement
     */
    private static function parseWhereBasic(WhereBasic $where): string
    {
        [$column, $operation, $value, $boolean] = array_values(
            $where->obtainCondition()
        );

        return sprintf(
            '%s %s %s ?',
            static::clearToInjection($boolean),
            static::wrapColumn(Expression::new($column)),
            static::clearToInjection($operation),
        );
    }

    /**
     * parse a WhereNested to a valid condition on statement
     */
    private static function parseWhereNested(WhereNested $where): string
    {
        [$conditions, $boolean] = array_values($where->obtainCondition());

        $condition = array_map(function (Where $where) {
            return self::callWhereParse($where);
        }, $conditions);

        return sprintf(
            '%s (%s)',
            static::clearToInjection($boolean),
            static::ltrimBoolean(implode(' ', $condition)),
        );
    }

    /**
     * call function to parse where
     */
    private static function callWhereParse(Where $where): string
    {
        return call_user_func(
            [self::class, 'parse' . basename($where::class)],
            $where,
        );
    }

    /**
     * concatenate a array with columns to a string
     */
    private static function concatenateColumns(array $columns): string
    {
        return empty($columns) ? '*' : implode(', ', $columns);
    }

    /**
     * remove boolean operator for the beginning of statement
     */
    private static function ltrimBoolean(string $statement): string
    {
        return array_reduce(['and', 'or'], function (string $acc, string $bool): string {
            return ltrim(ltrim($acc, $bool));
        }, $statement);
    }

    /**
     * remove the strings [';', '--'] form string
     * 
     * @param string $string
     * 
     * @return string
     */
    public static function clearToInjection(string $string): string
    {
        return preg_replace('/;|--/', '', $string);
    }

    /**
     * wrap expression for "from" clause
     * 
     * @param Expression $ep expression to wrap
     * 
     * @return string
     */
    protected static function wrapFrom(Expression $ep): string
    {
        if ($ep instanceof ExpressionRaw) {
            return static::wrapColumn($ep);
        }

        return static::wrapTable($ep->value());
    }

    /**
     * wrap table to query
     * 
     * @param string $table name of table
     * 
     * @return string the wrapped table's name
     */
    public static function wrapTable(string $table): string
    {
        return '`' . static::wrapWord(str_replace('`', '``', $table)) . '`';
    }

    /**
     * wrap a the first word of string
     * 
     * @param string $word the string
     * 
     * @return string wrapped word
     */
    public static function wrapWord(string $word): string
    {
        if (str_contains($word, ' ')) {
            $word = strtok($word, ' ');
        }

        return static::clearToInjection($word);
    }

    /**
     * parse Expression to wrapped column 
     * 
     * @param Expression $column the column to wrap
     * 
     * @return string wrapped column (with alias if was)
     */
    public static function wrapColumn(Expression $column): string
    {
        if ($column instanceof ExpressionRaw) {
            $value = $column->value();
        } else {
            $value = implode('.', array_map(
                [static::class, 'wrapSegment'],
                explode('.', $column->value())
            ));
        }

        if (null !== $as = $column->alias()) {
            $value = $value . ' AS ' . Grammar::wrapSegment($as);
        }

        return $value;
    }

    /**
     * wrap a segment of column
     * 
     * @param string $segment the segment to wrap
     * 
     * @return string wrapped segment
     */
    protected static function wrapSegment(string $segment): string
    {
        if ('*' === $segment) {
            return $segment;
        }

        return sprintf(
            '`%s`',
            static::wrapWord(str_replace('`', '``', $segment)),
        );
    }

    /**
     * wrap and implode all the columns
     * 
     * @param array $columns the columns to wrap
     * 
     * @return string the concatenated columns
     */
    public static function columnize(array $columns): string
    {
        return implode(', ', array_map(
            [static::class, 'wrapColumn'],
            $columns ?: [Expression::new('*')],
        ));
    }
}