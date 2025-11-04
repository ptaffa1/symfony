<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

// 1) Request
$request = Request::createFromGlobals();

// 2) Rutas (usa UNA sola fuente: src/app.php o config/routes.php)
$routes = require __DIR__ . '/../src/app.php'; // o '../config/routes.php'

// 3) Infra de routing
$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);
$requestStack = new RequestStack();

// 4) Dispatcher + listeners core
$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, $requestStack));
$dispatcher->addSubscriber(new ResponseListener('UTF-8'));
$dispatcher->addSubscriber(new ErrorListener('Calendar\\Controller\\ErrorController::exception'));

// 5) Tus subscribers (módulo 6) + (opcional) módulo 8
$dispatcher->addSubscriber(new \Simplex\ContentLengthListener());
$dispatcher->addSubscriber(new \Simplex\GoogleListener());
$dispatcher->addSubscriber(new \Simplex\StringResponseListener()); // si lo creaste
$dispatcher->addSubscriber(new \Simplex\CachingListener());




// 6) Resolvers
$controllerResolver = new ControllerResolver();
$argumentResolver   = new ArgumentResolver();

$kernel = new \Simplex\Framework($dispatcher, $controllerResolver, $requestStack, $argumentResolver);
$kernel = new \Symfony\Component\HttpKernel\HttpCache\HttpCache(
  $kernel,
  new \Symfony\Component\HttpKernel\HttpCache\Store(__DIR__.'/../cache')
  // , new \Symfony\Component\HttpKernel\HttpCache\Esi()
  // , ['debug'=>true]
);
