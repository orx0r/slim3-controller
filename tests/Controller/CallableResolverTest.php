<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2016 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/master/LICENSE.md (MIT License)
 */
namespace Orx0r\Slim\Controller\Tests;

use Orx0r\Slim\Controller\CallableResolver;
use Slim\Container;
use Orx0r\Slim\Controller\Tests\Mocks\CallableTest;
use Orx0r\Slim\Controller\Tests\Mocks\InvokableTest;

/**
 * Class CallableResolverTest
 *
 * @package Orx0r\Slim\Controller\Tests
 * @covers Orx0r\Slim\Controller\CallableResolver
 */
class CallableResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        CallableTest::$CalledCount = 0;
        InvokableTest::$CalledCount = 0;
        $this->container = new Container();
    }

    public function testClosure()
    {
        $test = function () {
            static $called_count = 0;

            return $called_count++;
        };
        $resolver = new CallableResolver($this->container);
        $callable = $resolver->resolve($test);
        $callable();
        $this->assertEquals(1, $callable());
    }

    public function testFunctionName()
    {
        // @codingStandardsIgnoreStart
        function testCallable()
        {
            static $called_count = 0;

            return $called_count++;
        }

        ;
        // @codingStandardsIgnoreEnd
        $resolver = new CallableResolver($this->container);
        $callable = $resolver->resolve(__NAMESPACE__ . '\testCallable');
        $callable();
        $this->assertEquals(1, $callable());
    }

    public function testObjMethodArray()
    {
        $obj = new CallableTest();
        $resolver = new CallableResolver($this->container);
        $callable = $resolver->resolve([$obj, 'toCall']);
        $callable();
        $this->assertEquals(1, CallableTest::$CalledCount);
    }

    public function testSlimCallable()
    {
        $resolver = new CallableResolver($this->container);
        $callable = $resolver->resolve('Orx0r\Slim\Controller\Tests\Mocks\CallableTest:toCall');
        $callable();
        $this->assertEquals(1, CallableTest::$CalledCount);
    }

    public function testSlimCallableContainer()
    {
        $resolver = new CallableResolver($this->container);
        $resolver->resolve('Orx0r\Slim\Controller\Tests\Mocks\CallableTest:toCall');
        $this->assertEquals($this->container, CallableTest::$CalledContainer);
    }

    public function testContainer()
    {
        $this->container['callable_service'] = new CallableTest();
        $resolver = new CallableResolver($this->container);
        $callable = $resolver->resolve('callable_service:toCall');
        $callable();
        $this->assertEquals(1, CallableTest::$CalledCount);
    }

    public function testResolutionToAnInvokableClassInContainer()
    {
        $this->container['an_invokable'] = function ($c) {
            return new InvokableTest();
        };
        $resolver = new CallableResolver($this->container);
        $callable = $resolver->resolve('an_invokable');
        $callable();
        $this->assertEquals(1, InvokableTest::$CalledCount);
    }

    public function testResolutionToAnInvokableClass()
    {
        $resolver = new CallableResolver($this->container);
        $callable = $resolver->resolve('Orx0r\Slim\Controller\Tests\Mocks\InvokableTest');
        $callable();
        $this->assertEquals(1, InvokableTest::$CalledCount);
    }

    public function testMethodNotFoundThrowException()
    {
        $this->container['callable_service'] = new CallableTest();
        $resolver = new CallableResolver($this->container);
        $this->setExpectedException('\RuntimeException');
        $resolver->resolve('callable_service:noFound');
    }

    public function testFunctionNotFoundThrowException()
    {
        $resolver = new CallableResolver($this->container);
        $this->setExpectedException('\RuntimeException');
        $resolver->resolve('noFound');
    }

    public function testClassNotFoundThrowException()
    {
        $resolver = new CallableResolver($this->container);
        $this->setExpectedException('\RuntimeException', 'Callable Unknown does not exist');
        $resolver->resolve('Unknown:notFound');
    }

    public function testClassWithControllerNamespace()
    {
        $resolver = new CallableResolver($this->container, 'Orx0r\Slim\Controller\Tests\Mocks');
        $callable = $resolver->resolve('CallableTest:toCall');
        $callable();
        $this->assertEquals(1, CallableTest::$CalledCount);
    }

    public function testClassWithControllerNamespaceNotFoundThrowException()
    {
        $resolver = new CallableResolver($this->container, 'Orx0r\Slim\Controller\Tests\Mocks');
        $this->setExpectedException('\RuntimeException', 'Callable Orx0r\Slim\Controller\Tests\Mocks\Unknown does not exist');
        $resolver->resolve('Unknown:notFound');
    }
}
