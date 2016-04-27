<?php

namespace app\controllers;

use Orx0r\Slim\Controller\Controller;

class MainController extends Controller
{
    public function actionIndex()
    {
        return $this->response->getBody()->write('Hello, Anonymous');
    }

    public function actionGreet($greet, $name = 'Anonymous')
    {
        return $this->render('main/greet.html', compact('greet', 'name'));
    }
}
