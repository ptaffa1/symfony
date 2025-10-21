<?php
//config/routes.php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;//RouteCollection vive en vendor/symfony/routing/….Este archivo no responde nada por sí mismo: solo define rutas y devuelve la colección.

$routes = new RouteCollection();// crea un objeto donde almacena r y a la vez agrega rutas a traves de Route

//Ruta con parametro dinamico{name}
$routes->add('hello', new Route(//registrás una ruta con nombre hello (el nombre es para identificarla
    '/hello/{name}',//{name} es placeholder (variable de ruta)  //hello: Ruta /hello/{name} que llama al método hello() del HelloController y pasa el parámetro name.

    ['_controller' => 'App\\Controller\\HelloController::hello']
));

//Ruta simple "home"
$routes->add('home', new Route(//home: Ruta / que llama al método home() del HelloController.
    '/',//Ruta home para el path /
    ['_controller' => 'App\\Controller\\HelloController::home']//_controller: En lugar de usar closures como antes, ahora definimos el controlador con el formato App\Controller\HelloController::home (el namespace y método).
));

return $routes;//Exportás la colección de rutas para que el front controller la requiera y use.

//Aca definimos las rutas y las asociamos con las acciones de HelloController

//Define dos rutas: home (/) y hello (/hello/{name})
//home: Ruta / que llama al método home() del HelloController.
//hello: Ruta /hello/{name} que llama al método hello() del HelloController y pasa el parámetro name.
//_controller: En lugar de usar closures como antes, ahora definimos el controlador con el formato App\Controller\HelloController::home (el namespace y método).