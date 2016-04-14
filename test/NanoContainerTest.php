<?php

namespace Snapshotpl\NanoContainer\Test;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\NotFoundException;
use PHPUnit_Framework_TestCase;
use Snapshotpl\NanoContainer\NanoContainer;
use Snapshotpl\NanoContainer\NanoContainerException;
use Snapshotpl\NanoContainer\ServiceNotFoundException;

/**
 * NanoContainerTest
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class NanoContainerTest extends PHPUnit_Framework_TestCase
{
    private $container;


    public function setUp()
    {
        $this->container = new NanoContainer([
            'boo' => function() {
                return 'bar';
            },
            'use-dependency' => function (ContainerInterface $container) {
                return $container->get('boo') . 'baz';
            },
            'invalid' => function() {
                throw new \Exception();
            },
            'awesome' => function (ContainerInterface $container, $id) {
                return sprintf('Called with %s', $id);
            },
        ]);
    }

    public function testNotFound()
    {
        $result = $this->container->has('not-found');

        $this->assertFalse($result);
    }

    public function testFound()
    {
        $result = $this->container->has('boo');

        $this->assertTrue($result);
    }

    public function testGetExistingService()
    {
        $result = $this->container->get('boo');

        $this->assertSame('bar', $result);
    }

    public function testNotGetNotExistingService()
    {
        $this->setExpectedException(NotFoundException::class);

        $this->container->get('not-found');
    }

    public function testNotExistingServiceExceptionContainsId()
    {
        try {
            $this->container->get('not-found');
        } catch (ServiceNotFoundException $ex) {
            $this->assertSame('not-found', $ex->getServiceId());
        }
    }

    public function testThrowExceptionIfServiceIsInvalid()
    {
        $this->setExpectedException(NanoContainerException::class);

        $this->container->get('invalid');
    }

    public function testThrowedExceptionIfServiceIsInvalidContainsPrevException()
    {
        try {
            $this->container->get('invalid');
        } catch (NanoContainerException $exception) {
            $this->assertInstanceOf(\Exception::class, $exception->getPrevious());
        }
    }

    public function testThrowedExceptionIfServiceIsInvalidContainsServiceId()
    {
        try {
            $this->container->get('invalid');
        } catch (NanoContainerException $exception) {
            $this->assertSame('invalid', $exception->getServiceId());
        }
    }

    public function testFactoryCanUseContainer()
    {
        $result = $this->container->get('use-dependency');

        $this->assertSame('barbaz', $result);
    }

    public function testFactoryCanReachCalledId()
    {
        $result = $this->container->get('awesome');

        $this->assertSame('Called with awesome', $result);
    }
}
