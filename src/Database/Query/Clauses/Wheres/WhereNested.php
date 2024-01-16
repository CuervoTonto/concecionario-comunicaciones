<?php

namespace Src\Database\Query\Clauses\Wheres;

class WhereNested implements Where
{
    /**
     * list of conditions
     * 
     * @var array<\Src\Database\Query\Clauses\Wheres\Where>
     */
    private array $conditions;

    /**
     * boolean operator to cancatenate condition
     */
    private string $boolean;

    /**
     * make a instance of class
     */
    public function __construct(array $conditions, string $boolean = 'and')
    {
        $this->boolean = $boolean;
        $this->addConditionsArray($conditions);
    }

    /**
     * add a array of conditions
     */
    public function addConditionsArray(array $conditions)
    {
        foreach ($conditions as $key => $condition) {
            if (is_string($key)) {
                $condition = [$key, '=', $condition];
            }

            $this->addCondition($condition);
        }
    }

    /**
     * add a single condition
     */
    public function addCondition(array $condition)
    {
        if (isset($condition[0]) && is_array($condition[0])) {
            $this->conditions[] = new WhereNested($condition);
        } else {
            $this->conditions[] = new WhereBasic(
                ...array_replace([null, null, null, $this->boolean], $condition)
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function obtainCondition(): array
    {
        return [
            'conditions' => $this->conditions,
            'boolean' => $this->boolean,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function obtainBindingsValues(): array
    {
        $func = function (Where $where): array {
            return $where->obtainBindingsValues();
        };

        return array_merge_recursive(
            ...array_map($func, $this->conditions)
        );
    }
}