<?php

namespace Src\Models;

use RuntimeException;
use Src\Database\Query\Builder;

class QueryBuilder extends Builder
{
    /**
     * the model insatnce
     */
    private string $model;

    /**
     * build a instance fo ModelIntermediary
     * 
     * @param string $model class of model
     */
    public function __construct(string $model)
    {
        $this->setModelClass($model);
        parent::__construct($model::connection());
    }

    /**
     * set the model's class to builder
     * 
     * @param string $model class of model
     * 
     * @return void
     */
    private function setModelClass(string $model): void
    {
        if (! class_exists($model) || ! is_a($model, Model::class, true)) {
            throw new RuntimeException('invalid model\'s class');
        }

        $this->model = $model;
    }

    /**
     * create register
     */
    public function create(array $data): Model
    {
        if (! parent::insert($data)) return null;

        return $this->model::query()->where(
            'id', parent::getConnection()->pdo()->lastInsertId()
        )->first();
    }

    /**
     * fetch registers
     * 
     * @return array<Model>
     */
    public function get(): array
    {
        return array_map(function (object $obj) {
            return new ($this->model)(get_object_vars($obj));
        }, parent::get());
    }

    /**
     * fetch first matched register
     */
    public function first(): null|Model
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    /**
     * find a register by primary key
     */
    public function find(int|float|bool $primary): Model
    {
        return parent::where($this->model::_primary(), $primary)->first();
    }
}