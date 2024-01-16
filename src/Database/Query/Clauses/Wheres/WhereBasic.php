<?php 

namespace Src\Database\Query\Clauses\Wheres;

use RuntimeException;
use Src\Utils\Assertion;

class WhereBasic implements Where
{
    /**
     * column of the condition
     */
    private string $column;

    /**
     * operation of the condition
     */
    private string $operation;

    /**
     * value comparerd on condition
     */
    private mixed $value;

    /**
     * boolean operator to cancatenate condition
     */
    private string $boolean;

    /**
     * make a instance of class
     */
    public function __construct(
        string $column,
        mixed $operation,
        mixed $value,
        string $boolean
    ) {
        [$operation, $value] = $this->parseOperationValue($operation, $value);
        $boolean = $this->parseBoolean($boolean);

        $this->column = $column;
        $this->operation = $operation;
        $this->value = $value;
        $this->boolean = $boolean;
    }

    /**
     * parse the operation and the value
     */
    private function parseOperationValue(mixed $operation, mixed $value = null): array
    {
        if (is_null($value)) {
            [$value, $operation] = [$operation, '='];
        } 

        if ($value !== null && ! is_string($operation)) {
            throw new RuntimeException('invalid condition operation');
        }

        return [$operation, $value];
    }

    /**
     * parse the boolean
     */
    private function parseBoolean(string $boolean): string
    {
        if (! in_array($boolean, ['and', 'or'])) {
            $boolean = 'and';
        }

        return $boolean;
    }

    /**
     * {@inheritDoc}
     */
    public function obtainCondition(): array
    {
        return [
            'column' => $this->column,
            'operation' => $this->operation,
            'value' => $this->value,
            'boolean' => $this->boolean
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function obtainBindingsValues(): array
    {
        return [
            $this->value
        ];
    }
}