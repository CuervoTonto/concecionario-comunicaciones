<?php 

namespace Src\Validator\Rules;

interface Rule
{
    /**
     * validate the given data
     */
    public function validate(mixed $value): bool;

    /**
     * make/obtain the message for fail on validation
     */
    public function failMessage(string $field): string;
}