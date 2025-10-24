<?php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

// Ruta a la página principal
$routes->add('home', new Route('/', [
    '_controller' => 'Calendar\\Controller\\LeapYearController::index',
]));

// Ruta para el cálculo de año bisiesto
$routes->add('is_leap_year', new Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => 'Calendar\\Controller\\LeapYearController::index',
]));

return $routes;
//Las rutas se definen como siempre, pero ahora estamos utilizando un namespace Calendar para nuestra aplicación.

/*$routes->add(): Define las rutas. Aquí agregamos dos rutas: 
home: Ruta principal / que llama a LeapYearController::index.
is_leap_year: Ruta /is_leap_year/{year} que llama a LeapYearController::index y pasa el parámetro year.

'_controller': Especifica qué controlador y método deben ejecutarse cuando se solicita la ruta. */