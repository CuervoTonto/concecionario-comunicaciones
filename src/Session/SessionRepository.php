<?php

namespace Src\Session;

class SessionRepository
{
    /**
     * session id
     */
    private string $file;

    /**
     * creates a new instance of sessionRepository
     * 
     * @param string $path path of session file
     */
    public static function new(string $path): static
    {
        return new static($path);
    }

    /**
     * build a new instance of sessionRepository
     * 
     * @param string $path path of session file
     */
    protected function __construct(string $path)
    {
        $this->file = $path;
        $this->initialize();
    }

    /**
     * initialize respository
     * 
     * @return void
     */
    private function initialize(): void
    {
        if (! file_exists($this->file)) {
            fclose(fopen($this->file, 'a+'));
        }
    }

    /**
     * obtains data from repository
     * 
     * @return array
     */
    public function fetch(): array
    {
        return unserialize(file_get_contents($this->file)) ?: [];
    }

    /**
     * save data on repository
     * 
     * @return void
     */
    public function save(array $newData): void
    {
        file_put_contents($this->file, serialize($newData), LOCK_EX);
    }

    /**
     * clear data
     * 
     * @return void
     */
    public function clear(): void
    {
        file_put_contents($this->file, serialize([]), LOCK_EX);
    }
}