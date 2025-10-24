<?php

//Este archivo es el punto de entrada para las solicitudes del cliente, el front controller. Aquí se recibe la solicitud y se pasa al Framework.
// Autoload y configuración de rutas
require __DIR__.'/../vendor/autoload.php';

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

// Crear la solicitud a partir de las superglobales
$request = Request::createFromGlobals();

// Cargar las rutas
$routes = include __DIR__.'/../src/app.php';

// Configurar el contexto de la solicitud
$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

// Resolver los controladores y los argumentos
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

// Crear el framework y manejar la solicitud
$framework = new Framework($matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

// Enviar la respuesta
$response->send();
//Aquí usamos la clase Framework que creamos para manejar la solicitud y devolver la respuesta.