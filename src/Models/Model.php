<?php

namespace Src\Models;

use Src\Classes\Globals;
use Src\Database\Connection;
use Src\Database\DBInitiator;
use Src\Database\Manager;
use Src\Database\Query\Builder;

abstract class Model
{
    /**
     * original attributes
     * 
     * @var array<mixed>
     */
    private array $original;

    /**
     * the attributes of model
     * 
     * @var array<mixed>
     */
    private array $attributes;

    /**
     * obtains the table target by the model
     * 
     * @return string the table's name
     */
    abstract public static function _table(): string;

    /**
     * obtains the primary key
     * 
     * @return string|null the primary key name
     */
    public static function _primary(): string|null
    {
        return 'id';
    }

    /**
     * build a instance of Model
     * 
     * @param array $attributes the attributes of the model
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * set the attributes of the model
     * 
     * @param array<string, mixed> $attributes value of attributes
     * 
     * @return void
     */
    private function setAttributes(array $attributes): void
    {
        $this->original = $attributes;
        $this->attributes = $this->applyGetters($attributes);
    }

    /**
     * call a method (if exists) to interact/modify parameter 
     * 
     * @param string $method the method to call
     * @param mixed $parameter the parameter to use
     * 
     * @return mixed the parameter with method applied
     */
    private static function applyCall(string $method, mixed $parameter): mixed
    {
        if (method_exists(static::class, $method)) {
            $parameter = static::{$method}($parameter);
        }

        return $parameter;
    }

    /**
     * apply getters to attributes
     * 
     * @param array $attributes attributes to apply getters
     * 
     * @return array attributes with getters applied
     */
    public static function applyGetters(array $attributes): array
    {
        foreach ($attributes as $name => &$value) {
            $value = static::applyCall("{$name}Getter", $value);
        }

        return $attributes;
    }

    /**
     * apply setters to attributes
     * 
     * @param array $attributes attributes to apply setters
     * 
     * @return array attributes with setters applied
     */
    public static function applySetters(array $attributes): array
    {
        foreach ($attributes as $name => &$value) {
            $value = static::applyCall("{$name}Setter", $value);
        }

        return $attributes;
    }

    /**
     * create a register with the data
     * 
     * @param array<string, mixed> $data data for register
     * 
     * @return static
     */
    public static function create(array $data): null|static
    {
        return static::query()->create(static::applySetters($data));
    }

    /**
     * update register of the model
     * 
     * @param array $data the new data
     * 
     * @return static
     */
    public function update(array $data): static
    {
        if (static::query()->where($this->original)->update($data)) {
            $this->setAttributes(
                $this->find($this->attributes[$this->_primary()])->toArray()
            );
        }

        return $this;
    }
    
    /**
     * delete register of the model
     * 
     * @return null|static
     */
    public function delete(): null|static
    {
        if (static::query()->where($this->original)->limit(1)->delete()) {
            $this->setAttributes([]);
        }

        return $this;
    }

    /**
     * update the register with the current data
     * 
     * @return null|static
     */
    public function save(): null|static
    {
        return $this->update($this->attributes);
    }

    /**
     * find a register by primary key
     * 
     * @param int|float|bool|string $primary value of primary key
     * 
     * @return static
     */
    public static function find(int|float|bool|string $primary): static
    {
        return static::query()->where(static::_primary(), $primary)->first();
    }

    /**
     * obtains registers
     * 
     * @return array<Model>
     */
    public static function get(): array
    {
        return static::query()->get();
    }

    /**
     * add where clause to query
     * 
     * @param string $column the column name
     * @param mixed $operation the condition operation
     * @param mixed $value the value for condition
     * 
     * @return QueryBuilder
     */
    public static function where(
        string|array $column,
        mixed $operation = null,
        mixed $value = null,
    ): QueryBuilder {
        return static::query()->where($column, $operation, $value, 'and');
    }

    /**
     * add where clause to query
     * 
     * @param string $column the column name
     * @param mixed $operation the condition operation
     * @param mixed $value the value for condition
     * 
     * @return QueryBuilder
     */
    public static function orwhere(
        string|array $column,
        mixed $operation = null,
        mixed $value = null,
    ): QueryBuilder {
        return static::query()->where($column, $operation, $value, 'or');
    }

    /**
     * set the limit for query register fetched/affecteds
     * 
     * @param null|int $amount the limit of registers
     * 
     * @return QueryBuilder
     */
    public static function limit(?int $amount): QueryBuilder
    {
        return static::query()->limit($amount);
    }

    /**
     * set order by clause
     * 
     * @param string $column the referenced column
     * @param string $direction the order directio (asc|desc)
     * 
     * @return QueryBuilder
     */
    public static function orderBy(string $column, string $direction = 'asc'): QueryBuilder
    {
        return static::query()->orderBy($column, $direction);
    }

    /**
     * set join clause of type Inner
     * 
     * @param string $from the table or subquery (Builder)
     * @param null|string $first the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $second the second column to join
     * 
     * @return QueryBuilder
     */
    public static function innerJoin(
        string $from,
        ?string $first = null,
        ?string $operation = null,
        ?string $second = null,
    ): QueryBuilder {
        return static::query()->innerJoin($from, $first, $operation, $second);
    }

    /**
     * add join clause (subquery) of type Inner
     * 
     * @param Builder $from the table or subquery (Builder)
     * @param string $as the alias for subquery
     * @param null|string $first the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $second the second column to join
     * 
     * @return QueryBuilder
     */
    public static function innerJoinSub(
        Builder $from,
        string $as,
        ?string $first = null,
        ?string $operation = null,
        ?string $second = null,
    ): QueryBuilder {
        return static::query()->innerJoinSub($from, $as, $first, $operation, $second);
    }

    /**
     * set join clause of type Left
     * 
     * @param string $from the table or subquery (Builder)
     * @param null|string $first the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $second the second column to join
     * 
     * @return QueryBuilder
     */
    public static function leftJoin(
        string $from,
        ?string $first = null,
        ?string $operation = null,
        ?string $second = null,
    ): QueryBuilder {
        return static::query()->leftJoin($from, $first, $operation, $second);
    }

    /**
     * set join clause (subquery) of type Left
     * 
     * @param Builder $from the table or subquery (Builder)
     * @param string $as the alias for subquery
     * @param null|string $first the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $second the second column to join
     * 
     * @return QueryBuilder
     */
    public static function leftJoinSub(
        Builder $from,
        string $as,
        ?string $first = null,
        ?string $operation = null,
        ?string $second = null,
    ): QueryBuilder {
        return static::query()->leftJoinSub($from, $as, $first, $operation, $second);
    }

    /**
     * set join clause of type Right
     * 
     * @param Builder|string $from the table or subquery (Builder)
     * @param null|string $first the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $second the second column to join
     * 
     * @return QueryBuilder
     */
    public static function rightJoin(
        Builder|string $from,
        ?string $first = null,
        ?string $operation = null,
        ?string $second = null,
    ): QueryBuilder {
        return static::query()->rightJoin($from, $first, $operation, $second);
    }

    /**
     * set join clause (subquery) of type Right
     * 
     * @param Builder $from the table or subquery (Builder)
     * @param string $as the alias for subquery
     * @param null|string $first the first column to join
     * @param null|string $operation the join operation (=, >, >=)
     * @param null|string $second the second column to join
     * 
     * @return QueryBuilder
     */
    public static function rightJoinSub(
        Builder $from,
        string $as,
        ?string $first = null,
        ?string $operation = null,
        ?string $second = null,
    ): QueryBuilder {
        return static::query()->rightJoinSub($from, $as, $first, $operation, $second);
    }

    /**
     * obtains a connection of db for model
     * 
     * @return Connection
     */
    public static function connection(): Connection
    {
        // initiate the db manager
        DBInitiator::init();

        /** @var Manager */
        $manager = Globals::get('db.manager');

        return $manager->connection(static::class);
    }

    /**
     * create a instance of query builder
     * 
     * @return QueryBuilder
     */
    public static function query(): QueryBuilder
    {
        return (new QueryBuilder(static::class))->from(static::_table());
    }

    /**
     * obtains the oringal values on array
     * 
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->original;
    }

    /**
     * handle petition to property
     */
    public function __get(string $attr): mixed
    {
        return $this->attributes[$attr];
    }

    /**
     * handle the assignation of undefined property
     */
    public function __set(string $attr, mixed $value): void
    {
        $this->attributes[$attr] = $value;
    }

    /**
     * handle call to unacessible/undefined methods
     */
    public static function __callStatic($name, $arguments) 
    {
        return static::query()->{$name}(...$arguments);
    }
}