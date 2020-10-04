<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Domains;

use LifenPag\Asaas\V3\{
    Collections\Collection,
    Entities\Entity,
    Exceptions\ValidationsErrorException,
    Http\HttpClient,
};

use InvalidArgumentException;

abstract class Domain
{
    protected static $entity;
    protected static $collection;

    public function __construct(?Entity $entity = null)
    {
        if ($entity !== null) {
            self::$entity = $entity;
        }

        return;
    }

    private static function validateEntityType(Entity $entity): void
    {
        if (!is_a($entity, static::$entityClass)) {
            throw new InvalidArgumentException('Entity must be an intance of ' . static::$entityClass);
        }
    }

    public static function create(Entity $entity): self
    {
        self::validateEntityType($entity);

        $entity->validate();

        $response = HttpClient::create($entity, static::$modelName);

        return new static(new static::$entityClass($response));
    }

    public static function update(array $request, string $id): self
    {
        $response = HttpClient::update($request, $id, static::$modelName);

        return new static(new static::$entityClass($response));
    }

    public function delete(string $id): self
    {
        $response = HttpClient::delete($id, static::$modelName);

        return new static(new static::$entityClass($response));
    }

    public function restore(string $id): self
    {
        $response = HttpClient::restore($id, static::$modelName);

        return new static(new static::$entityClass($response));
    }

    public static function find(string $id): self
    {
        $response = HttpClient::request(
            'GET',
            static::$modelName . '/' . $id,
        );

        return new static(new static::$entityClass($response));
    }

    public static function all(array $filters = ['limit' => -1]): Collection
    {
        $response = HttpClient::all($filters, static::$modelName);

        return new static::$collectionClass($response);
    }

    public static function where(array $filters = []): Collection
    {
        return self::all($filters);
    }

    public function get(): Entity
    {
        return self::$entity;
    }
}
