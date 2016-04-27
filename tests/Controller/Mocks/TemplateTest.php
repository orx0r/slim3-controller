<?php
/**
 * Implementation of controller for Slim Framework v3 (https://github.com/orx0r/slim3-controller)
 *
 * @link https://github.com/orx0r/slim3-controller
 * @license https://raw.githubusercontent.com/orx0r/slim3-controller/master/LICENSE
 */
namespace Orx0r\Slim\Controller\Tests\Mocks;

use Psr\Http\Message\ResponseInterface;

/**
 * Mock object for Orx0r\Slim\Controller\Tests\Controller
 */
class TemplateTest
{
    public function render(ResponseInterface $response, $template, $data = [])
    {
        $response->getBody()->write('hello');
        return $response;
    }
}