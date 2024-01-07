<?php 

namespace Src\Session;

use App\Application;

class Session
{
    /**
     * identificator
     */
    private readonly string $id;

    /**
     * flash messages
     */
    private array $flashes;

    /**
     * error flash messages
     */
    private array $errors;

    /**
     * initialization vector
     */
    private string $iv;

    /**
     * session token
     */
    private string $token;

    /**
     * respository to session
     */
    private SessionRepository $repository;

    /**
     * session configurations
     */
    private SessionConfig $config;

    /**
     * make a instance of class
     * 
     * @param string $id Session ID
     */
    public function __construct(string $id)
    {
        $config = new SessionConfig();
        $repository = new SessionRepository($id, $config->get('repository'));

        $this->id = $id;
        $this->config = $config;
        $this->repository = $repository;
        $this->flashes = [];
        $this->errors = [];
        $this->iv = $this->repository->get('_key', bin2hex(random_bytes(8)));
        // $this->token = $this->fetchToken($this->repository->get('_token', $this->generateToken()));

        $this->useToken();
    }

    private function useToken(): void
    {
        $token = $this->repository->get('_token');
        $expire = $this->repository->get('_token.time');

        if (is_null($token) || $expire < time()) {
            $token = $this->generateToken();
        }

        $this->token = $token;
    }

    /**
     * generate a new token
     */
    private function generateToken()
    {
        $token = openssl_encrypt($this->id, 'aes256', 'INV', iv: $this->iv);
        $this->saveToken($token, 10);

        return $token;
    }

    /**
     * save the token for a time on repository
     */
    private function saveToken(string $token, int $time): void
    {
        $this->repository->save([
            '_token' => $token,
            '_token.time' => time() + $time
        ]);
    }

    /**
     * save data of session into repository
     */
    public function save()
    {
        $this->repository->save([
            'flashes' => $this->flashes,
            'errors' => $this->errors,
            '_iv' => $this->iv
        ]);
    }

    /**
     * get a data from session
     */
    public function get(string $dataName): mixed
    {
        return $this->repository->get($dataName);
    }

    /**
     * add flash message to session
     */
    public function flash(string $name, string $value): void
    {
        $this->flashes[$name] = $value;
    }

    /**
     * add flash error to session
     * 
     * @param string $name identificator to the error
     * @param string|array<string> $value the error(s) messages(s)
     */
    public function error(string $name, string|array $value): void
    {
        $this->errors[$name] = $value;
    }

    /**
     * get flash message
     */
    public function getFlash(string $name): mixed
    {
        return $this->flashes[$name];
    }

    /**
     * get flash message
     */
    public function getError(string $name): mixed
    {
        return $this->errors[$name];
    }

    /**
     * get the errors array
     */
    public function allFlashes(): array
    {
        return array_merge($this->repository->get('flashes', []), $this->flashes);
    }

    /**
     * get the errors array
     */
    public function allErrors(): array
    {
        return array_merge($this->repository->get('errors', []), $this->errors);
    }

    /**
     * get the session identificator
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * get the respository for session
     */
    public function repository(): SessionRepository
    {
        return $this->repository;
    }
}