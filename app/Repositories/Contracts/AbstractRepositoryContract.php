<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AbstractRepositoryContract
{
    /**
     * Get the repository's model.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    public function getModel(): Model;

    /**
     * Retrieve all data.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function get();

    /**
     * Retrieve the first row of data.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function first(): ?Model;

    /**
     * Find a single row of data by its' primary key id.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id);

    /**
     * @param string $uuid
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByUuid(string $uuid): ?Model;

    /**
     * Create a resource.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model;

    /**
     * Fetch the first resource found or create a new one.
     *
     * @param array $attributes
     * @param array $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes, array $values = []): Model;

    /**
     * Update the first resource found or create a new one.
     *
     * @param array $attributes
     * @param array $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreate(array $attributes, array $values = []): Model;

    /**
     * Update a resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @return \App\Repositories\Contracts\AbstractRepositoryContract
     */
    public function update(Model $model, array $data): self;

    /**
     * Delete a resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \App\Repositories\Contracts\AbstractRepositoryContract
     */
    public function destroy(Model $model): self;
}
