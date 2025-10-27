<?php
namespace Calendar\Controller;//Namespace del  app.

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//Importa el modelo y Request/Response.
final class LeapYearController
{
    public function index(Request $request, int $year): Response  //Acción index. El ArgumentResolver inyecta Request y convierte year a int.
    {
        $ly = new LeapYear();

        return $ly->isLeapYear($year)
            ? new Response('Yep, this is a leap year!')
            : new Response('Nope, this is not a leap year.');//Devuelve un Response en función de la lógica del modelo.
    }
}//nstancia el modelo que sabe chequear años bisiestos.
