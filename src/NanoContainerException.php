<?php

namespace Snapshotpl\NanoContainer;

use Exception;
use Interop\Container\Exception\ContainerException;

/**
 * NanoContainerException
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class NanoContainerException extends Exception implements ContainerException
{
    private $id;

    public function __construct($id, $message = "", Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->id = $id;
    }

    public function getServiceId()
    {
        return $this->id;
    }
}
