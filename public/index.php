<?php
//public/index.php

require_once __DIR__ . '/../vendor/autoload.php';//carga automatica de clases (Composer autoload). activa el autoload de Composer (sin esto, Route, Request, etc. no existen).

use Symfony\Component\HttpFoundation\Request;//importa las clases (namespace)
use Symfony\Component\HttpFoundation\Response;//Request/Response (HttpFoundation)
use Symfony\Component\Routing\RequestContext;//RequestContext (info de la request para el router: método, host, esquema, path).
use Symfony\Component\Routing\Matcher\UrlMatcher;//UrlMatcher (el que matchea la URL contra la RouteCollection)
use Symfony\Component\Routing\Exception\ResourceNotFoundException; // opcional: para usar el nombre corto en el catch 
use Symfony\Component\HttpKernel\Controller\ControllerResolver;//Resuelven el controlador y sus parámetros automáticamente.
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use App\Framework\Container;

//1) Construye la Request a partir de las superglobales PHP
$request = Request::createFromGlobals(); // o sea convierte $_GET, $_POST, $_SERVER, etc. en un objeto Request.
/*Ejemplo: si visitás http://localhost:8000/hello/Pedro, entonces:
$request->getPathInfo() será "/hello/Pedro".*/

//2)Cargar las rutas
$routes = require __DIR__ . '/../config/routes.php';//Ejecuta config/routes.php y guarda la RouteCollection en $routes.

//3)Preparar contexto de routing a partir de la Request
$context = new RequestContext();//RequestContext necesita saber método (GET, POST), host (localhost), esquema (http), base URL y path
$context->fromRequest($request);//fromRequest($request) lo rellena con los datos de la Request.
//Por ejemplo, si es GET /hello/Pedro en http://localhost:8000, quedará método = GET, host = localhost, esquema = http, pathInfo = /hello/Pedro.

//4) Matchear la URL entrante
$matcher = new UrlMatcher($routes, $context);//Creás el “emparejador” de URLs, con tus rutas y el contexto actual.

// resolvers de HttpKernel
$controllerResolver = new ControllerResolver();
$argumentResolver   = new ArgumentResolver();
try {
    // inyecta atributos de routing a la Request (clave para ArgumentResolver)
    $request->attributes->add($matcher->match($request->getPathInfo()));

    // resuelve callable del controlador (p.ej. [HelloController, 'hello'])
    $controller = $controllerResolver->getController($request);

    // resuelve argumentos correctos (p.ej. ($request, $name))
    $arguments  = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
    if (!$response instanceof Response) {
        $response = new Response((string) $response);
    }
} catch (ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
} catch (\Throwable $e) {
    $response = new Response('Error: '.$e->getMessage(), 500);
}


$response->send();//Envía headers + body al navegador.Acá termina el ciclo Request → Routing → Controller → Response.

//El index es el punto de entrada donde se recibe la solicitud, se procesan rutas y se resuelve el controlador adecuado

//Resuelve el controlador usando los resolvers (ControllerResolver y ArgumentResolver).
//Gestion de Errores. Si una ruta no se encuentra, se devuelve un 404, y si hay errores, se maneja con un 500


/**Flujo completo

Request → Routing → Controller: El Framework maneja la solicitud, el UrlMatcher busca la ruta, el ControllerResolver encuentra el controlador y el ArgumentResolver pasa los parámetros correctos.

Controller → Model: En LeapYearController, el modelo LeapYear es responsable de la lógica de negocios.

Response → Render → Send: Se devuelve una Response con el resultado, y se envía al navegador. */