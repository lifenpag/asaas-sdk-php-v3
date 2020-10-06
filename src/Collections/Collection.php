<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Collections;

use LifenPag\Asaas\V3\{
    Exceptions\EntityException,
    Interfaces\CollectionInterface,
    Entities\Entity
};

use stdClass;

abstract class Collection implements CollectionInterface
{
    /**
     * @var array $data Array holding data
     */
    protected $data = [];

    public function __construct(stdClass $collection)
    {
        $entity = static::ENTITY;

        foreach ($collection->data as $data) {
            $this->setData(new $entity($data));
        }
    }

    /**
     * Get the data's value
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Verifies if data collection is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->getData() === [];
    }

    /**
     * Undocumented function
     *
     * @return Entity
     */
    public function first(): Entity
    {
        if ($this->isEmpty()) {
            throw new EntityException(
                'collection data',
                'is empty',
            );
        }

        return $this->getData()[0];
    }

    /**
     * Set the value of data
     *
     * @param self $data
     *
     * @return self
     */
    public function setData(Entity $data): self
    {
        $this->data[] = $data;

        return $this;
    }

    public function map(callable $callback): Collection
    {
        $keys = array_keys($this->data);

        $items = array_map($callback, $this->data, $keys);

        $object = new stdClass();
        $object->data = array_combine($keys, $items);

        return new static($object);
    }
}
