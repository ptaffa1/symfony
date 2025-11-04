<?php
namespace Calendar\Controller;//Namespace del  app.

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//Importa el modelo y Request/Response.
class LeapYearController
{
    public function index(int $year): string
    {
        $leapYear = new LeapYear();
        return $leapYear->isLeapYear($year)
            ? 'Yep, this is a leap year! ' . rand()
            : 'Nope, this is not a leap year. ' . rand();
    }
}

//nstancia el modelo que sabe chequear años bisiestos.
