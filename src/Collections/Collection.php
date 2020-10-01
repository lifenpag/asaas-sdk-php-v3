<?php

namespace LifenPag\ApiAsaas\V3\Collections;

abstract class Collection
{
    public $data;

    public function __construct($collection)
    {
        $model = static::MODEL;

        foreach ($collection->data as $data) {
            $this->setData(new $model($data));
        }
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */
    public function setData($data)
    {
        $this->data[] = $data;

        return $this;
    }
}