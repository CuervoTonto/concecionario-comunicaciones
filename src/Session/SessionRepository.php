<?php 

namespace Src\Session;

use Closure;
use Src\Contracts\Repository\RepositoryInterface;

class SessionRepository implements RepositoryInterface
{
    /**
     * session id
     */
    private string $id;

    /**
     * last fetch data
     */
    private array $data;

    /**
     * session options
     */
    private array $opts;

    /**
     * make a instance of class
     */
    public function __construct(string $id, array $options = [])
    {
        $this->data = [];
        $this->id = $id;
        $this->opts = $options;

        $this->refresh();
    }

    /**
     * start php session
     */
    public function open(): void
    {
        session_id($this->id);
        session_start($this->opts);
    }

    /**
     * close php session
     */
    public function close(): void
    {
        session_write_close();
    }

    /**
     * execute a closure with the repository stream open and then close
     */
    public function action(Closure $action): mixed
    {
        $this->open();
        $result = $action();
        $this->close();
        
        return $result;
    }

    /**
     * fetch the data
     */
    public function fetch(): mixed
    {
        return $this->action(function () {
            return $_SESSION ?? [];
        });
    }

    /**
     * get a data
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->data[$name] ?? $default;
    }

    /**
     * {@inheritDoc}
     */
    public function save(array $data): void
    {
        $this->action(function () use ($data) {
            foreach ($data as $key => $value) $_SESSION[$key] = $value;
        });

        $this->refresh();
    }

    /**
     * refresh the data using the one from repository
     */
    public function refresh(): void
    {
        $this->data = $this->fetch();
    }

    /**
     * clear the data of repository
     */
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        return $this->data;
    }
}