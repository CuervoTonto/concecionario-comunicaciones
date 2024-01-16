<?php 

namespace Src\Database\Query\Clauses\Wheres;

interface Where
{
    /**
     * get the condition
     */
    public function obtainCondition(): array;

    /**
     * get the values to use for binding
     */
    public function obtainBindingsValues(): array;
}