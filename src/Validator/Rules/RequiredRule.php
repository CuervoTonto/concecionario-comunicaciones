<?php 

namespace Src\Validator\Rules;

class RequiredRule implements Rule
{
    /**
     * {@inheritDoc}
     */
    public function validate(mixed $value): bool
    {
        return $value !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function failMessage(string $field): string
    {
        return sprintf(
            'the field "%s" is required',
            $field,
        );
    }
}