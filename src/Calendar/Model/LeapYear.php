<?php
namespace Calendar\Model;

final class LeapYear
{
    public function isLeapYear(?int $year = null): bool
    {
        $year ??= (int) date('Y');
        return 0 == $year % 400 || (0 == $year % 4 && 0 != $year % 100);
    }
}
/*Regla clásica de año bisiesto.
Si no pasás año, usa el año actual.*/