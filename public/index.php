<?php
require __DIR__ . '/../vendor/autoload.php'; //Carga el autoloader de Composer. Sin esto no existen las clases (Request, UrlMatcher, tus namespaces, etc.).

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
//Importa las clases que vamos a usar: tu Framework, la Request, el Contexto de routing, el UrlMatcher y los resolvers de controlador/argumentos.

$request = Request::createFromGlobals();//Construye la Request a partir de $_SERVER, $_GET, $_POST, etc.
$routes  = require __DIR__ . '/../src/app.php';//Carga la RouteCollection que definiste en src/app.php

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();
$argumentResolver   = new ArgumentResolver();
//Prepara el contexto (método, host, esquema, path, …), el matcher de rutas y los resolvers para determinar el controlador y sus parámetros.

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new \Simplex\ContentLengthListener());
$dispatcher->addSubscriber(new \Simplex\GoogleListener());

// Y al instanciar tu Framework con sus dependencias., pasale el dispatcher primero:
$framework = new \Simplex\Framework(
    $dispatcher,
    $matcher,
    $controllerResolver,
    $argumentResolver
);

$framework->handle($request)->send();
//Orquesta: handle() procesa la request y devuelve una Response, que se envía al navegador con send().
