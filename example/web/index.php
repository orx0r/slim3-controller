<?php

use Orx0r\Slim\Controller\CallableResolver;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$c = new \Slim\Container;

$c['view'] = function ($container) {
    $view = new \Slim\Views\Twig( __DIR__ . '/../templates');
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

$app = new \Slim\App($c);

// one of default version
$app->get('/hello/{name}', function (Request $request, Response $response, $args) use ($app){
    $response->write('Hello, ' . $args['name']);

    return $response;
});

// version with controller
$app->get('/greet/{greet}/[{name}]', 'app\\controllers\\MainController:greet');

// in small Slim app you can use controllerNamespace for resolving
// all your controllers in same namespace by specifying only controller name

// register CallableResolver. pass second parameter as controllerNamespace
$c['callableResolver'] = function ($container) {
    return new CallableResolver($container, 'app\\controllers');
};

// version with controllerNamespace
$app->get('/greet', 'MainController:index');

$app->run();
