<?php

namespace Snapshotpl\NanoContainer;

use Exception;
use Interop\Container\Exception\NotFoundException;

/**
 * ServiceNotFoundException
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class ServiceNotFoundException extends Exception implements NotFoundException
{
    private $id;

    public function __construct($id, $message = "")
    {
        parent::__construct($message);
        $this->id = $id;
    }

    public function getServiceId()
    {
        return $this->id;
    }
}
