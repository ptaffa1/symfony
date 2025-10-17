<?php
//public/index.php

require_once __DIR__ . '/../vendor/autoload.php';//carga automatica de clases (Composer autoload). activa el autoload de Composer (sin esto, Route, Request, etc. no existen).

use Symfony\Component\HttpFoundation\Request;//importa las clases (namespace)
use Symfony\Component\HttpFoundation\Response;//Request/Response (HttpFoundation)
use Symfony\Component\Routing\RequestContext;//RequestContext (info de la request para el router: método, host, esquema, path).
use Symfony\Component\Routing\Matcher\UrlMatcher;//UrlMatcher (el que matchea la URL contra la RouteCollection)
use Symfony\Component\Routing\Exception\ResourceNotFoundException; // opcional: para usar el nombre corto en el catch
use Twig\Environment;
use Twig\Loader\FilesystemLoader;//para cargar las plantillas desde la carpeta templates/, y Environment para configurar Twig.


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

try {
    $parameters = $matcher->match($request->getPathInfo()); //$matcher->match(pathInfo) intenta encontrar qué ruta coincide con el path
    // $parameters incluye: _route, _controller y variables (p.ej. 'name')//Si encuentra:$parameters será un array con:
    //'_route': el nombre de la ruta (ej. 'hello').
    //'_controller': el controlador que definiste (closure).
    //Variables de ruta (ej. 'name' => 'Pedro').

    // Guarda el nombre de la ruta ANTES de hacer unset
    $routeName = $parameters['_route'];

    // Copia para argumentos del controlador
    $args = $parameters;

    // 5) Resolver controlador y argumentos
    $controller = $args['_controller'];//$controller: extraés el controlador (closure).
    unset($args['_controller'], $args['_route']);//Limpiás '_controller' y '_route' del array, así te quedan solo variables (ej. ['name' => 'Pedro']).

    // Inyectamos la Request como primer argumento, seguido de las variables de ruta
    $responseContent = $controller($request, ...array_values($args));//Ejecutás el controlador pasándole:
    /*$request primero (para que lo tenga a mano),y las variables de ruta en orden (spread operator ...).
    $responseContent será lo que devuelva el controlador:
    En nuestro caso, un string, p. ej. "Hello Pedro".*/

    // 7) Crear respuesta Twig
    $loader = new FilesystemLoader(__DIR__ . '/../template');  // Cargar plantillas desde 'templates/'
    $twig = new Environment($loader);

    if ($routeName === 'home') {
        // No necesita variables
        $html = $twig->render('home.twig', [
            'message' => 'Welcome to Mini Symfony!',
        ]);
    } elseif ($routeName === 'hello') {
        // Usa la variable de ruta 'name' (sigue viva en $parameters/$args original)
        $name = $parameters['name'] ?? 'World';
        $html = $twig->render('home.twig', [
            'message' => 'Hello ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
        ]);
    } else {
        // Fallback: si no renderizamos con Twig, usa lo que devolvió el controlador
        $html = (string) $responseContent;
    }
   // 8) Normalizar a Response
    $response = $responseContent instanceof Response
        ? $responseContent
        : new Response($html);
} catch (ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);//404: si no hubo match de ruta.
} catch (Exception $e) {
    $response = new Response('Error: '.$e->getMessage(), 500);//500: cualquier otra excepción (te ayuda a no “romper” la app).
}


$response->send();//Envía headers + body al navegador.Acá termina el ciclo Request → Routing → Controller → Response.