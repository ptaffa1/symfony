<?php
namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index(Request $request, int $year): Response //index(): Método que recibe un parámetro year y usa la clase LeapYear para calcular si el año es bisiesto. Si es bisiesto, devuelve una respuesta positiva, y si no, una negativa.
    {
        $leapYear = new LeapYear();
        if ($leapYear->isLeapYear($year)) {
            return new Response('Si, es anio bisiesto!');
        }

        return new Response('Nope, no es anio bisiesto.');
    }
}
//Este controlador recibe el parámetro year de la URL, y pasa la información a la clase LeapYear para determinar si es un año bisiesto.
//Este es un controlador básico que maneja la lógica de la aplicación, en este caso, el cálculo de años bisiestos.