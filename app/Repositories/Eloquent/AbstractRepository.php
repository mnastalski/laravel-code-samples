<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\AbstractRepositoryContract;
use InvalidArgumentException;

abstract class AbstractRepository implements AbstractRepositoryContract
{
    /**
     * Path to the model's namespace.
     *
     * @var string
     */
    private const MODELS_NAMESPACE = 'App\\Models\\';

    /**
     * Path to the repositories's namespace.
     *
     * @var string
     */
    private const REPOSITORIES_NAMESPACE = 'App\\Repositories\\';

    /**
     * The repository's model.
     *
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $model;

    /**
     * Get the repository's model.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    public function getModel(): Model
    {
        if ($model = $this->model) {
            return $model;
        }

        $class = self::MODELS_NAMESPACE . $this->guessModelName();

        return new $class;
    }

    /**
     * Guess the model's name based on repository's class name.
     *
     * @return string
     */
    protected function guessModelName(): string
    {
        $namespace = self::REPOSITORIES_NAMESPACE;
        $repository = get_class($this);

        if (!Str::startsWith($repository, $namespace)) {
            throw new InvalidArgumentException("Repository's namespace [{$repository}] is invalid, [{$namespace}] expected.");
        }

        $class = str_replace($namespace, '', $repository);

        return Str::replaceLast('Repository', '', $class);
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        return $this->getModel()->get();
    }

    /**
     * @inheritdoc
     */
    public function first(): ?Model
    {
        return $this->getModel()->first();
    }

    /**
     * @inheritdoc
     */
    public function find(int $id)
    {
        return $this->getModel()->find($id);
    }

    /**
     * @inheritdoc
     */
    public function findByUuid(string $uuid): ?Model
    {
        return $this
            ->getModel()
            ->where('uuid', $uuid)
            ->first();
    }

    /**
     * @inheritdoc
     */
    public function create(array $data): Model
    {
        return $this->getModel()->create($data);
    }

    /**
     * @inheritdoc
     */
    public function firstOrCreate(array $attributes, array $values = []): Model
    {
        return $this->getModel()->firstOrCreate($attributes, $values);
    }

    /**
     * @inheritdoc
     */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->getModel()->updateOrCreate($attributes, $values);
    }

    /**
     * @inheritdoc
     */
    public function update(Model $model, array $data): AbstractRepositoryContract
    {
        $model->update($data);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function destroy(Model $model): AbstractRepositoryContract
    {
        $model->delete();

        return $this;
    }
}
