<?php 

namespace App\Framework\Controller;

use App\Framework\Container;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Sympony\Component\HttpFoundation\Response;

abstract class BaseController
{
    protected function render(string $template, array $params = []):HttpFoundationResponse //render(): Método que usará los controladores que extiendan BaseController para renderizar plantillas Twig.
    {
        $html = Container::twig()->render($template, $params);//Llama a Container::twig()->render() para renderizar la plantilla.

        return new HttpFoundationResponse($html); //Devuelve un objeto Response con el contenido HTML renderizado.

    }
}//Este archivo crea una clase base para controladores (BaseController), que centraliza el método render(). Así no necesitas repetir el código de renderización de Twig.
//Proporciona un metodo render para que todos los controladores lo usen
//Container::twig() para obtener la instancia de Twig y renderizar las vistas
//Llama a Container::twig()->render() para renderizar la plantilla.
//Devuelve un objeto Response con el contenido HTML renderizado.
