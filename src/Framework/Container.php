<?php

namespace App\Framework;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class Container 
{
    private static ?Environment $twig = null; 


    public static function twig():Environment //twig(): Método estático que devuelve la instancia de Twig (si no está instanciada, la crea).
    {
        if(self::$twig === null){//self::$twig === null: Verifica si Twig ya ha sido instanciado. Si no, lo crea
            $loader = new FilesystemLoader(__DIR__ . '/../../template');//FilesystemLoader: Carga las plantillas desde la carpeta template/ (relativa a src/Framework)
            self::$twig = new Environment($loader);//Environment: Crea el entorno de Twig
        }
        return self::$twig;
    }
}
//Crea una capa de servicios CENTRALIZADA, instancia Twig. Hace que haya disponibilidad para otros controladores sin tener que INCIALIZARLO RAPIDAMENTE
//$twig: Variable estática que guarda la instancia de Twig.
//self::$twig === null: Verifica si Twig ya ha sido instanciado. Si no, lo crea
//FilesystemLoader: Carga las plantillas desde la carpeta templates/ (relativa a src/Framework)
//twig(): Método estático que devuelve la instancia de Twig (si no está instanciada, la crea).
//Environment: Crea el entorno de Twig.