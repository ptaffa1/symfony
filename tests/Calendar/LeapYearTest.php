<?php
declare(strict_types=1);

use Calendar\Model\LeapYear;  // o new \Calendar\Model\LeapYear()

use PHPUnit\Framework\TestCase;

final class LeapYearTest extends TestCase
{
    public function testLeapYears(): void
    {
        $ly = new LeapYear();
        $this->assertTrue($ly->isLeapYear(2000));
        $this->assertTrue($ly->isLeapYear(2024));
        $this->assertFalse($ly->isLeapYear(1900));
        $this->assertFalse($ly->isLeapYear(2023));
    }
}
//Verifica la lógica pura sin HTTP ni routing.Casos límite incluidos (1900 no es bisiesto; 2000 sí).