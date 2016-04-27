<?php
/**
 * Implementation of controller for Slim Framework v3 (https://github.com/orx0r/slim3-controller)
 *
 * @link https://github.com/orx0r/slim3-controller
 * @license https://raw.githubusercontent.com/orx0r/slim3-controller/master/LICENSE
 */
namespace Orx0r\Slim\Controller\Tests;

use Orx0r\Slim\Controller\Controller;
use Orx0r\Slim\Controller\Tests\Mocks\TemplateTest;
use Slim\Container;
use Orx0r\Slim\Controller\Tests\Mocks\CallableTest;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Body;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;

/**
 * Class ControllerTest
 * 
 * @package Orx0r\Slim\Controller\Tests
 * @covers Orx0r\Slim\Controller\Controller
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testContainer()
    {
        $this->container['callable_service'] = new CallableTest();
        $controller = new Controller($this->container);
        $callable_service = $controller->callable_service;
        $this->assertInstanceOf('Orx0r\Slim\Controller\Tests\Mocks\CallableTest', $callable_service);

//        $settings = ['a', 'b'];
//        $container = new Container();
//        $container['settings'] = $settings;
//        $controller = new Controller($container);

//        $this->assertInstanceOf('\Slim\Container', $controller->getContainer());
//        $this->assertEquals(true, isset($controller->settings));
//        $this->assertEquals($settings, $controller->settings);
    }

    public function testPropertyNotFoundThrowException()
    {
        $controller = new Controller($this->container);
        $this->setExpectedException('\RuntimeException');
        $controller->noFound;
    }

    public function testCallNotExistAction()
    {
        $controller = new ExampleController($this->container);
        $this->setExpectedException('\RuntimeException');
        $controller->noFound();
    }

    public function testCallActionWithArguments()
    {
        $env = Environment::mock();
        $uri = Uri::createFromString('https://example.com:80');
        $headers = new Headers();
        $cookies = [];
        $serverParams = $env->all();
        $body = new Body(fopen('php://temp', 'r+'));
        $request = new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
        $response = new Response();
        $arguments = ['greet' => 'hello'];

        $controller = new ExampleController($this->container);
        $handler = new RequestResponse();

        $result = $handler([$controller, 'hello'], $request, $response, $arguments);
        $this->assertInstanceOf('Slim\Http\Response', $result);
        $this->assertEquals('hello', (string)$response->getBody());
    }

    public function testRender()
    {
        $this->container['view'] = new TemplateTest();

        $env = Environment::mock();
        $uri = Uri::createFromString('https://example.com:80');
        $headers = new Headers();
        $cookies = [];
        $serverParams = $env->all();
        $body = new Body(fopen('php://temp', 'r+'));
        $request = new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
        $response = new Response();
        $arguments = ['greet' => 'hello'];

        $controller = new ExampleController($this->container);
        $handler = new RequestResponse();

        $result = $handler([$controller, 'template'], $request, $response, $arguments);
        $this->assertInstanceOf('Slim\Http\Response', $result);
        $this->assertEquals('hello', (string)$response->getBody());
    }
}

/**
 * Class ExampleController
 * @package Orx0r\Slim\Controller\Tests
 *
 * @method test
 * @method args
 */
class ExampleController extends Controller
{
    public function actionHello($greet)
    {
        $body = $this->response->getBody();
        $body->write($greet);
        return $this->response->withBody($body);
    }

    public function actionTemplate()
    {
        return $this->render('hello.html');
    }
}