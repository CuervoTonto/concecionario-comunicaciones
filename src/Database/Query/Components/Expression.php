<?php

namespace Src\Database\Query\Components;

class Expression
{
    /**
     * expression value
     */
    private string $value;

    /**
     * the alias for the expression
     */
    private ?string $alias;

    /**
     * Build a instance of Expression
     * 
     * @param string $value the value of expression
     * @param null|string $alias alias for expression
     */
    public function __construct(string $value, ?string $alias = null)
    {
        $this->value = $value;
        $this->alias = $alias;
    }

    /**
     * create a new instance of class
     * 
     * @param string $value the value of expression
     * @param null|string $alias alias for expression
     * 
     * @return static
     */
    public static function new(string $value, ?string $alias = null): static
    {
        return new static($value, $alias);
    }

    /**
     * obtains the value
     * 
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * obtains the alias
     * 
     * @return null|string
     */
    public function alias(): ?string
    {
        return $this->alias;
    }
}