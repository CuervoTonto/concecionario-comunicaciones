<?php

namespace Src\Console\Input;

use RuntimeException;

abstract class AbstractInputToken
{
    /**
     * valid modes to argument token
     * 
     * @var array<InputTokenMode>
     */
    protected array $validModes = [
        InputTokenMode::REQUIRED,
        InputTokenMode::OPTIONAL,
        InputTokenMode::NO_VALUE,
    ];

    /**
     * input token's modes
     * 
     * @var InputTokenMode[]
     */
    private array $modes;

    /**
     * input token's value
     */
    private null|string $value;

    /**
     * input token's name
     */
    private string $name;

    /**
     * build a instance of InputArgument
     * 
     * @param string $name argument's name
     * @param InputTokenMode[]|InputTokenMode $mode argument's mode(s)
     * @param null|string $value default values of input (null to no default)
     */
    public function __construct(
        string $name,
        array|InputTokenMode $mode,
        ?string $value = null,
    ) {
        $this->setName($name);
        $this->setMode($mode);
        $this->setValue($value);
    }

    /**
     * set the mode of the input
     * 
     * @param array<InputTokenMode>|InputTokenMode $mode argument's mode(s)
     * 
     * @return void
     */
    public function setMode(array|InputTokenMode $mode): void
    {
        if (! is_array($mode)) {
            $mode = [$mode];
        }

        foreach ($mode as $m) {
            $this->validateMode($m);
        }

        $this->modes = $mode;
    }

    /**
     * validate the mode is valid for the instance
     * 
     * @param InputTokenMode $mode argument's mode(s)
     * 
     * @return void
     */
    protected function validateMode(InputTokenMode $mode): void
    {
        if (! $mode instanceof InputTokenMode) {
            $message = 'invalid input token [' . get_class($mode) . ']';
        } elseif (! in_array($mode, $this->validModes)) {
            $message = "invalid mode [$mode->name] for [" . __CLASS__ . ']';
        }

        if (isset($message)) {
            throw new RuntimeException($message);
        }
    }

    /**
     * set the default value for argument
     * 
     * @param null|string $value the argument's value
     * 
     * @return void
     */
    public function setValue(null|string $value): void
    {
        $this->value = $value;
    }

    /**
     * set the name for token
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * obtains the token's name
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * obtains the modes for input token
     * 
     * @return array<InputTokenMode>
     */
    public function modes(): array
    {
        return $this->modes;
    }

    /**
     * obtains the value of the input token
     */
    public function value(): null|string
    {
        return $this->value;
    }

    /**
     * check if input modes contains "REQUIRED"
     */
    public function isRequired(): bool
    {
        return in_array(InputTokenMode::REQUIRED, $this->modes);
    }

    /**
     * check if input modes contains "OPTIONAL"
     */
    public function isOptional(): bool
    {
        return in_array(InputTokenMode::OPTIONAL, $this->modes);
    }

    /**
     * check if input modes no contains "NO_VALUE"
     */
    public function needValue(): bool
    {
        return ! in_array(InputTokenMode::NO_VALUE, $this->modes);
    }
}