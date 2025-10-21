<?php
// src/Controller/HelloController.php
namespace App\Controller;//indica que el controlador esta dentro de la carpeta src/Controller

use App\Framework\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends BaseController //El controlador hereda de BaseController, por lo que puede usar el metodo render()
{
    public function home(Request $request): Response //home() renderiza la plantilla home.twig con un 'Welcome to Mini Symfony!
    {
        return $this->render('home.twig', [
            'message' => 'Welcome to Mini Symfony!'
        ]);
    }

    public function hello(Request $request, string $name): Response //hello(): renderiza la misma plantilla home.twig, pero con un mensaje dinámico usando el parámetro name de la ruta (/hello/{name}).
    {
        return $this->render('home.twig', [
            'message' => "Hello $name"
        ]);
    }
}
//Es un controlador de ejemplo que maneja las rutas definidas (/ y /hello/{name}).Se usa el metodo render() heredado del BaseController
//Hereda de BaseController y usa su metodo render()
//Define las acciones home y hello (para las rutas definidas)

