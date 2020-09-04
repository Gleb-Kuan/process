<?php

namespace Process\Test;

use Psr\Container\ContainerInterface;

class TestedContainer implements ContainerInterface
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->services[$id]);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->services[$id];
    }

    /**
     * @param string $id
     * @param $service
     */
    public function set($id, $service)
    {
        $this->services[$id] = $service;
    }
}