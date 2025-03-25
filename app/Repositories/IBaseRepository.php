<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * @param string $fieldName
     * @param mixed $value
     * @return Model|null
     */
    public function findFirstBy(string $fieldName, mixed $value): ?Model;

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}
