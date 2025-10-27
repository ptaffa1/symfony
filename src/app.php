<?php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Response;
//Importa las clases de routing y (opcionalmente) Response si usás closures.

$routes = new RouteCollection();//Estructura que almacenará todas las rutas.

$routes->add('home', new Route('/', [
    '_controller' => function () { return new Response('Welcome to Mini Symfony!'); },
]));
//Ruta home (/).El _controller aquí es una closure que devuelve un Response (útil para páginas simples o pruebas).



$routes->add('leap_year', new Route(
    '/is_leap_year/{year}',
    ['_controller' => 'Calendar\\Controller\\LeapYearController::index'],
    ['year' => '\d+'] // opcional
));
//Ruta con parámetro dinámico {year}. Apunta al método index() de tu LeapYearController.
return $routes;
//Devuelve la colección a index.php.