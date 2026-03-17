<?php

namespace Src\Session;
use Src\Classes\Globals;

class Session
{
    /**
     * session data
     */
    private array $data;

    /**
     * session id
     */
    private string $id;

    /**
     * repository for session data
     */
    private SessionRepository $repository;

    /**
     * session options
     */
    private array $options;

    /**
     * build a instance of Session
     * 
     * @param string|null $id identification of session
     * @param array<string, mixed> $options
     */
    public static function new(string|null $id, array $options = []): static
    {
        return new static($id, $options);
    }

    /**
     * build a instance of Session
     * 
     * @param string|null $id identification of session
     * @param array<string, mixed> $options
     */
    public function __construct(string|null $id, array $options = [])
    {
        $this->options = $this->replaceOptions($options);
        $this->id = $id ??= $this->createId();
        $this->repository = $this->createsRepository();
        $this->data = $this->repository->fetch();

        $this->initialize();
    }

    /**
     * initialize Session
     */
    private function initialize(): void
    {
        $this->initializeToken();
    }

    /**
     * initialize the token for session
     * 
     * @return void
     */
    private function initializeToken(): void
    {
        if (! $this->inTimeToken()) $this->regenerateToken();
    }

    /**
     * set a new value to the session token
     * 
     * @return void
     */
    private function regenerateToken(): void
    {
        $this->set('_token', hash('sha256', random_bytes(8)));
        $this->set('_token.expires_at', time() + $this->options['token.lifetime']);
    }

    /**
     * check if the token is valid for the current time (didn't expires)
     * 
     * @return bool
     */
    public function inTimeToken(): bool
    {
        // if no has token no is on time
        if (! $this->has('_token')) {
            return false;
        }

        // if token expiration is null (no exists) token always is on time
        if (null === $expires = $this->get('_token.expires_at')) {
            return true;
        }

        // if has expiration check if is out of time
        return $expires >= time();
    }

    /**
     * check a data is present on session
     * 
     * @param string|null $id identification of session
     * 
     * @return bool
     */
    private function has(int|string $index): bool
    {
        return array_key_exists($index, $this->data);
    }

    /**
     * saves the session data
     * 
     * @return void
     */
    public function save(): void
    {
        $this->data['_flash.old'] = $this->get('_flash.new', []);
        $this->data['_flash.new'] = [];

        $this->repository->save($this->data);
    }

    /**
     * obtains a data from repository
     * 
     * @param int|string $index the index of data
     * @param mixed $default default value on case of not have the indexed data
     * 
     * @return mixed data obtained
     */
    public function get(int|string $index, mixed $default = null): mixed
    {
        return $this->data[$index] ?? $default;
    }

    /**
     * set data to session
     * 
     * @param int|string $index the index of data
     * @param mixed $value data's value
     * 
     * @return void
     */
    public function set(int|string $index, mixed $value): void
    {
        $this->data[$index] = $value;
    }

    /**
     * obtains and remove a data from repository
     * 
     * @param int|string $index the index of data
     * @param mixed $default default value on case of not have the indexed data
     * 
     * @return void
     */
    public function pull(int|string $index, mixed $default = null): mixed
    {
        $data = $this->get($index, $default);
        $this->remove($index);

        return $data;
    }

    /**
     * remove a data from session
     * 
     * @param int|string $index the index of data
     * 
     * @return void
     */
    public function remove(int|string $index): void
    {
        unset($this->data[$index]);
    }

    /**
     * add flash message
     * 
     * @param string $name name of the flash message
     * @param string $message
     * 
     * @return void
     */
    public function flash(string $name, string $message): void
    {
        $this->data['_flash.new'][$name] = $message;
    }

    /**
     * obtains new flash message
     * 
     * @param string $name name of flash message
     * 
     * @return mixed flash message
     */
    public function fromNewFlash(string $name): mixed
    {
        return $this->data['_flash.new'][$name] ?? null;
    }

    /**
     * obtains old flash message
     * 
     * @param string $name name of flash message
     * 
     * @return mixed flash message
     */
    public function fromOldFlash(string $name): mixed
    {
        return $this->data['_flash.old'][$name] ?? null;
    }

    /**
     * obtains message from flash
     * 
     * @param string $name name of flash message
     * 
     * @return mixed flash message
     */
    public function getFlash(string $name): mixed
    {
        return $this->fromNewFlash($name) ?? $this->fromOldFlash($name);
    }

    /**
     * add a error to session, the message to same error would be accumalte
     * 
     * @param string $name error's name
     * @param string $message message to error
     * 
     * @return void
     */
    public function error(string $name, string $message): void
    {
        $this->data['_flash.new']['_errors'][$name][] = $message;
    }

    /**
     * check if error messages exists on session
     * 
     * @param string $name error's name
     * 
     * @return bool error exists
     */
    public function hasError(string $name): bool
    {
        return array_key_exists($name, $this->data['_flash.new']['_errors'])
            || array_key_exists($name, $this->data['_flash.old']['_errors']);
    }

    /**
     * obtains old flash messages to errors
     * 
     * @param string $name error's name
     * 
     * @return array<string>|null
     */
    public function getOldErrors(string $name): array|null
    {
        return $this->data['_flash.old']['_errors'][$name] ?? null;
    }
    
    /**
     * obtains new flash messages to errors
     * 
     * @param string $name error's name
     * 
     * @return array<string>|null
     */
    public function getNewErrors(string $name): array|null
    {
        return $this->data['_flash.new']['_errors'][$name] ?? null;
    }

    /**
     * obtains flash messages (old and news) to error
     * 
     * @param string $name error's name
     * 
     * @return array<string>|null
     */
    public function getAllErrors(string $name): array|null
    {
        return array_map('array_unique', array_merge_recursive(
            $this->getNewErrors($name) ?? [],
            $this->getOldErrors($name) ?? [],
        ));
    }

    /**
     * obtains all the flash error messages
     * 
     * @return array<string, array>
     */
    public function getErrors(): array
    {
        return array_map('array_unique', array_merge_recursive(
            $this->data['_flash.old']['_errors'] ?? [],
            $this->data['_flash.new']['_errors'] ?? [],
        ));
    }

    /**
     * creates a instanc of SessionRepository
     * 
     * @return SessionRepository repository instance
     */
    private function createsRepository(): SessionRepository
    {
        return SessionRepository::new(
            "{$this->options['repository.path']}/{$this->id}"
        );
    }

    /**
     * creates a random session id
     * 
     * @return string random id
     */
    private function createId(): string
    {
        return $this->options['repository.prefix'] . bin2hex(
            random_bytes(16)
        );
    }

    /**
     * replaces the default values of options (or add option)
     * 
     * @param array<string, mixed> $options new values for options
     * 
     * @return array<string, mixed> replaced options
     */
    private function replaceOptions(array $options): array
    {
        return array_replace($this->defaultOptions(), $options);
    }

    /**
     * obtains the default options values
     * 
     * @return array<string, mixed> default options
     */
    private function defaultOptions(): array
    {
        return [
            'repository.prefix' => 'session_',
            'repository.path' => Globals::get('root') . '/resources/tmp/session',
            'cookie.name' => 'SESSION_COOKIE',
            'cookie.lifetime' => 3600 * 24 * 365 * 2,
            'token.lifetime' => 60,
        ];
    }

    /**
     * obtains the session id
     * 
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    public function repository(): SessionRepository
    {
        return $this->repository;
    }
}