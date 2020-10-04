<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Collections;

use LifenPag\Asaas\V3\Interfaces\CollectionInterface;

use stdClass;

abstract class Collection
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
     * Set the value of data
     *
     * @param self $data
     *
     * @return self
     */
    public function setData($data): self
    {
        $this->data[] = $data;

        return $this;
    }

    public function map(callable $callback)
    {
        $keys = array_keys($this->data);

        $items = array_map($callback, $this->data, $keys);

        $object = new stdClass();
        $object->data = array_combine($keys, $items);

        return new static($object);
    }
}
