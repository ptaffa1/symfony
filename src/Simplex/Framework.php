<?php
namespace Simplex;
/*Simplex es un namespace personalizado que creamos para organizar nuestra clase de framework.
Podríamos haberlo llamado "MiFramework" o "FrameworkApp", pero preferimos usar algo corto como Simplex (esto es solo una convención, puedes cambiarlo).*/

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;//Importás ResourceNotFoundException para mapear 404.
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
//Dependés de interfaces (clave para tests con mocks)


/*Esta clase es el corazón de la separación de responsabilidades. La clase Framework se encarga de recibir una Request y devolver una Response.*/ 
class Framework
{
    public function __construct( //Constructores: El constructor inicia las dependencias necesarias para resolver la Request
    
        private UrlMatcherInterface $matcher, //$matcher: Es el encargado de hacer coincidir el path de la URL con las rutas definidas.
        private ControllerResolverInterface $controllerResolver,//$controllerResolver: Resuelve qué controlador debe ser ejecutado.
        private ArgumentResolverInterface $argumentResolver,//$argumentResolver: Resuelve los argumentos del controlador (por ejemplo, el $request y las variables de la URL).
    ){
    }


    /*Este es el método principal que orquesta todo el flujo de trabajo, es decir, toma la solicitud (Request), la procesa y devuelve una respuesta (Response).*/
    // /Actualiza el contexto del matcher con los datos de la Request.
    public function handle(Request $request): Response //handle(): Este método se encarga de recibir la Request y devolver la Response. Utiliza los resolvers para encontrar el controlador y pasarle los argumentos correctos.
    {
        //CONFIGURA EL CONTEXTO DE LA SOLICITUD
        $this->matcher->getContext()->fromRequest($request);//getContext() obtiene el contexto de la solicitud (datos como el método HTTP, host, etc.).
        //fromRequest($request) le pasa los datos del Request al UrlMatcher para que sepa qué URL está siendo solicitada.

        try{
            //Match la URL  con las rutas definidas
            $request->attributes->add($this->matcher->match($request->getPathInfo()));
            /*Se agrega el resultado de match(), que incluye la ruta coincidente y cualquier parámetro capturado, al objeto Request. 
            Esto actualiza la solicitud con los parámetros de la ruta (como {name}).*/ 

            //Resuelve el controlador y los parametros
            $controller = $this->controllerResolver->getController($request);//Utiliza el ControllerResolver para resolver qué controlador ejecutar (basado en los parámetros de la ruta).
            //Ejemplo: Si la ruta es /hello/{name}, el ControllerResolver buscará la acción correcta dentro del controlador HelloController (como hello(Request $request, $name)).
           
            $arguments = $this->argumentResolver->getArguments($request, $controller);//Usa el ArgumentResolver para resolver los parámetros del controlador.
            /*Aquí es donde la magia pasa: resolvemos automáticamente todos los parámetros que necesita el controlador 
            (como el parámetro $name de la ruta). Ejemplo: Si el controlador espera un parámetro $name, este se obtiene de los atributos de la solicitud. */



            //Ejecuta el controlador con los argumentos
            return call_user_func_array($controller, $arguments);
            
            return $response instanceof Response ? $response : new Response((string) $response);

        }catch (ResourceNotFoundException) { //ResourceNotFoundException: Si no se encuentra ninguna coincidencia con la URL solicitada, se devuelve un error 404.
            return new Response('Not found', 404);
        }catch(\Throwable) {//Exception: Cualquier otro error general se maneja devolviendo un error 500 (por ejemplo, problemas internos con el servidor).
            return new Response('An error occurred',500);
        }


    }

}

//handle(): Este método se encarga de recibir la Request y devolver la Response. Utiliza los resolvers para encontrar el controlador y pasarle los argumentos correctos.





















//Este archivo se encargará de manejar las solicitudes y las respuestas, siguiendo el principio de "Separation of Concerns".