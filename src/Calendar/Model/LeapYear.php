<?php
namespace Calendar\Model;
//Clase que calcula si un año es bisiesto o no.

class LeapYear 
{
    public function isLeapYear(?int $year = null): bool
    {
        if (null === $year) {
            $year = date('Y');
        }

        return 0 == $year % 400 || (0 == $year % 4 && 0 != $year % 100);
    }
}
//isLeapYear(): Método que verifica si el año dado es bisiesto. Si no se pasa un año, usa el año actual. 
//Calcula si el año es divisible por 4 y no por 100, a menos que sea divisible por 400.

