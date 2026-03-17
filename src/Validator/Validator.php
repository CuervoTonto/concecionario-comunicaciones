<?php 

namespace Src\Validator;

use Src\Contracts\Repository\RepositoryInterface;
use Src\Exceptions\ResponseException;
use Src\Http\Response;
use Src\Session\SessionRepository;
use Src\Utils\ClassHelper;
use Src\Validator\Rules\Rule;

class Validator
{
    /**
     * validation rules aliases
     */
    private array $rules = [
        'required' => 'Src\Validator\Rules\RequiredRule',
    ];

    /**
     * the data to validate
     */
    private array $data;

    /**
     * validations to improve in the data
     */
    private array $validations;

    /**
     * message on validation fail
     */
    private array $messages;

    /**
     * errors given on validation
     */
    private ?array $errors = null;

    /**
     * create a instance of class
     */
    public static function make(
        array $data,
        array $validations = [],
        array $messages = []
    ): static {
        return new static($data, $validations, $messages);
    }

    /**
     * make a instance of Validator
     * 
     * @param array<string, mixed> $data data to validate
     * @param array<string, string|array> $validations validations to improve on data
     * @param array<string, string> $messages messages to fail validations
     */
    public function __construct(
        array $data,
        array $validations = [],
        array $messages = []
    ) {
        $this->data = $data;
        $this->messages = $messages;
        $this->validations = [];

        $this->addValidations($validations);
    }

    /**
     * add validations to validator
     */
    public function addValidations(array $validations): void
    {
        foreach ($validations as $field => $rules) {
            $this->addRules($field, $rules);
        }
    }

    /**
     * aggregate the validation rules for the field
     */
    public function addRules(string $field, string|array $rules): void
    {
        if (is_string($rules)) {
            $this->addRules($field, explode('|', $rules));
            return;
        }

        foreach ($rules as &$rule) {
            $rule = $this->parseRule($rule);
        }

        $this->validations[$field] = array_merge(
            $this->validations[$field] ?? [],
            array_merge(...$rules)
        );
    }

    /**
     * parse the given rule
     */
    private function parseRule(string $rule): array
    {
        $rule = explode(':', $rule, 2);
        $rule[1] = explode(',', $rule[1] ?? '');

        return [$rule[0] => $rule[1]];
    }

    /**
     * validate the data
     */
    public function validate(): bool
    {
        $this->clearErrors();

        foreach ($this->validations as $field => $rules) {
            $this->fieldValidation($field, $rules);
        }

        return empty($this->errors);
    }

    /**
     * validate the data and redirect on case of fail
     * 
     * @param null|string $url url to redirection, null to "/"
     * @param null|RepositoryInterface $save repository where save the errors
     */
    public function validateRedirect(
        ?string $url = null,
        ?RepositoryInterface $save = null
    ): void {
        if ($this->validate() === true) {
            return;
        }

        $save->save(['errors' => array_merge(
            $save->all()['errors'] ?? [],
            $this->errors()
        )]);

        throw new ResponseException(Response::redirect($url));
    }

    /**
     * validate the field data
     */
    private function fieldValidation(string $field, array $rules): void
    {
        foreach ($rules as $rule => $parameters) {
            $this->validateAddingError($field, $rule, $parameters);
        }
    }

    /**
     * validate and add error on case of fail
     */
    private function validateAddingError(string $field, string $rule, array $parameters): void
    {
        /** @var Rule */
        $instance = new ($this->getRuleClass($rule))($parameters);

        if (! $instance->validate($this->data[$field] ?? null)) {
            $this->addError($field, $this->failMessage($field, $rule, $instance));
        }
    }

    /**
     * add error to validator
     */
    private function addError(string $field, string $error): void
    {
        $this->errors[$field][] = $error;
    }

    /**
     * obtain fail message to no passed validation
     */
    private function failMessage(string $field, string $rule, Rule $instance): string
    {
        return $this->messages["{$field}.{$rule}"]
            ?? $this->messages[$rule]
            ?? $instance->failMessage($field);
    }

    /**
     * get the rule class
     */
    public function getRuleClass(string $rule): string
    {
        return $this->rules[$rule] ?? $rule;
    }

    /**
     * clear the errors of the last validation
     */
    private function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * remove errors
     */
    public function flushErrors(): void
    {
        $this->errors = null;
    }

    /**
     * get validator errors
     */
    public function errors(): ?array
    {
        return $this->errors;
    }
}