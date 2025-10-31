<?php
namespace Calendar\Controller;//Namespace del  app.

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//Importa el modelo y Request/Response.
final class LeapYearController
{
   public function index(Request $request, int $year): Response
{
    $leapYear = new \Calendar\Model\LeapYear();

    if ($leapYear->isLeapYear($year)) {
        $response = new Response('Yep, this is a leap year! ' . rand()); // rand() para verlo variar
    } else {
        $response = new Response('Nope, this is not a leap year. ' . rand());
    }

    // cache público por 10 segundos
    $response->setPublic();
    $response->setTtl(10); // equivalente a setSharedMaxAge para proxies

    return $response;
}

}//nstancia el modelo que sabe chequear años bisiestos.
