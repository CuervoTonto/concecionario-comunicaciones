<?php

namespace Src\Console\Input;

class Input
{
    /**
     * tokens of input
     */
    private array $tokens;

    /**
     * arguments of input
     */
    private array $arguments;

    /**
     * long options of input
     */
    private array $longOptions;

    /**
     * short options of input
     */
    private array $shortOptions;

    /**
     * build a instance of Input
     * 
     * @param array<string> $tokens tokens for input
     */
    public function __construct(array $tokens)
    {
        $this->setTokens($tokens);
        $this->parse();
    }

    /**
     * set the tokens for input
     * 
     * @param array<string> $tokens
     * 
     * @return void
     */
    public function setTokens(array $tokens): void
    {
        $this->tokens = $tokens;
    }

    /**
     * parse and add the input's tokens
     */
    protected function parse(): void
    {
        foreach ($this->tokens as $token) {
            $type = match (true) {
                $this->isArg($token) => 'arg',
                $this->isLong($token) => 'long',
                $this->isShort($token) => 'short',
                default => 'arg',
            };

            match ($type) {
                'arg' => $this->addArg($this->parseArg($token)),
                'long' => $this->addLong(...$this->parseLong($token)),
                'short' => $this->addShort($this->parseShort($token)),
            };
        }
    }

    /**
     * parse token to argument
     * 
     * @param string $token token to parse
     * 
     * @return string parsed token
     */
    protected function parseArg(string $token): string
    {
        return $token;
    }

    /**
     * parse token to long option
     * 
     * @param string $token token to parse
     * 
     * @return array<string, string> parsed option (option => value)
     */
    protected function parseLong(string $token): array
    {
        $token = substr($token, 2);

        if (false === $pos = strpos($token, '=')) {
            return [$token, ''];
        }

        return [
            substr($token, 0, $pos),
            substr($token, $pos + 1)
        ];
    }

    /**
     * parse token to short option
     * 
     * @param string $token token to parse
     * 
     * @return string parsed option
     */
    protected function parseShort(string $token): string
    {
        return substr($token, 1);
    }

    /**
     * add a argument to input
     * 
     * @param string $argument argument's value
     * 
     * @return void
     */
    public function addArg(string $argument): void
    {
        $this->arguments[] = $argument;
    }

    /**
     * add a long option to input
     * 
     * @param string $opt option's name
     * @param string $value option's value
     * 
     * @return void
     */
    public function addLong(string $opt, string $value): void
    {
        $this->longOptions[$opt] = $value;
    }

    /**
     * add a short option to input
     * 
     * @param string $short option
     * 
     * @return void
     */
    public function addShort(string $short): void
    {
        $this->shortOptions[] = $short;
    }

    /**
     * check if token is for long option
     * 
     * @param string $token token to check
     * 
     * @return bool result of check
     */
    protected function isArg(string $token): bool
    {
        return ! str_starts_with($token, '-');
    }

    /**
     * check if token is for long option
     * 
     * @param string $token token to check
     * 
     * @return bool result of check
     */
    protected function isLong(string $token): bool
    {
        return $token !== '--' && str_starts_with($token, '--');
    }

    /**
     * check if token is for long option
     * 
     * @param string $token token to check
     * 
     * @return bool result of check
     */
    protected function isShort(string $token): bool
    {
        return $token !== '-' && str_starts_with($token, '-');
    }

    /**
     * parse and add a token for argument
     */
    protected function parseArgument(): void
    {
        // 
    }

    /**
     * parse and add a token for long option
     */
    protected function parseLongOption(): void
    {
        // 
    }

    /**
     * parse and add a token for short option
     */
    protected function parseShortOption(): void
    {
        // 
    }

    /**
     * add argument to input
     */
    protected function addArgument(string $name, string $token): void
    {
        $this->arguments[$name] = $token;
    }

    /**
     * add long option to input
     */
    protected function addLongOption(string $name, string $value): void
    {
        $this->longOptions[$name] = $value;
    }

    /**
     * add short option to input
     */
    protected function addShortOption(string $name, bool $value): void
    {
        $this->shortOptions[] = $value;
    }

    /**
     * obtains a argument from input
     */
    public function argument(int $index): null|string
    {
        return $this->arguments[$index] ?? null;
    }

    /**
     * obtains a long option from input
     */
    public function longOption(string $name): null|string
    {
        return $this->longOptions[$name] ?? null;
    }

    /**
     * obtains a short option from input
     */
    public function shortOption(string $name): bool
    {
        return array_search($name, $this->shortOptions, true);
    }

    /**
     * obtains the tokens of input
     */
    protected function getTokens(): array
    {
        return $this->tokens;
    }
}