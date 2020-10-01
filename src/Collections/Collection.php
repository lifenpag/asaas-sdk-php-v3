<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Collections;

abstract class Collection implements CollectionInterface
{
    /**
     * @var array $data Array holding data
     */
    protected $data = [];

    public function __construct(self $collection)
    {
        $model = static::MODEL;

        foreach ($collection->data as $data) {
            $this->setData(new $model($data));
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
    public function setData(self $data): self
    {
        $this->data[] = $data;

        return $this;
    }
}
