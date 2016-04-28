<?php
/**
 * Implementation of controller for Slim Framework v3 (https://github.com/orx0r/slim3-controller)
 *
 * @link https://github.com/orx0r/slim3-controller
 * @license https://raw.githubusercontent.com/orx0r/slim3-controller/master/LICENSE
 */
namespace Orx0r\Slim\Controller;

use Interop\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class Controller
 * @package Orx0r\Slim\Controller
 */
class Controller
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->has($property)) {
            return $this->container->get($property);
        }

        throw new \RuntimeException(sprintf('Property %s does not exist', get_class($this) . "::$property"));
    }

    public function __call($name, $arguments)
    {
        $method = 'action' . ucfirst($name);
        if (method_exists($this, $method)) {
            list($this->request, $this->response, $params) = $arguments;
            return call_user_func_array([$this, $method], $params);
        }

        throw new \RuntimeException(sprintf('Method %s does not exist', get_class($this) . "::$name()"));
    }

    /**
     * @deprecated
     *
     * @param string $template
     * @param array  $data
     *
     * @return mixed
     */
    public function render($template, $data = [])
    {
        return $this->container->get('view')->render($this->response, $template, $data);
    }
}
