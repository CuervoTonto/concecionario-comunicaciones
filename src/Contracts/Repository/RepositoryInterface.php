<?php 

namespace Src\Contracts\Repository;

interface RepositoryInterface
{
    /**
     * save/store data
     */
    public function save(array $data): void;

    /**
     * get data from repository
     */
    public function all(): array;
}