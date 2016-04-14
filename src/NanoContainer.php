<?php

namespace Snapshotpl\NanoContainer;

use Exception;
use Interop\Container\ContainerInterface;

/**
 * NanoContainer
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
final class NanoContainer implements ContainerInterface
{
    private $factories = [];

    public function __construct(array $factories)
    {
        foreach ($factories as $id => $factory) {
            $this->setFactory($id, $factory);
        }
    }

    private function setFactory($id, callable $factory)
    {
        $this->factories[$id] = $factory;
    }

    public function get($id)
    {
        if (!isset($this->factories[$id])) {
            throw new ServiceNotFoundException($id, sprintf('Service with id "%s" not found in container', $id));
        }
        try {
            $service = $this->factories[$id]($this, $id);
        } catch (Exception $exception) {
            throw new NanoContainerException($id, sprintf('Service with id "%s" cannot be created', $id), $exception);
        }
        return $service;
    }

    public function has($id)
    {
        return isset($this->factories[$id]);
    }
}
