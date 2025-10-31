<?php
namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface; //nuevo
final class Framework implements HttpKernelInterface 
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private UrlMatcherInterface $matcher,
        private ControllerResolverInterface $controllerResolver,
        private ArgumentResolverInterface $argumentResolver,
    ) {}

   // Firma EXACTA de HttpKernelInterface:
    public function handle(
        Request $request,
        int $type = HttpKernelInterface::MAIN_REQUEST,
        bool $catch = true
        ): Response {
        $this->matcher->getContext()->fromRequest($request);

        try {
            // 1) Hacer match de la ruta e inyectar atributos (_controller, vars, etc.)
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            // 2) Resolver controlador y argumentos
            $controller = $this->controllerResolver->getController($request);
            $arguments  = $this->argumentResolver->getArguments($request, $controller);

            // 3) Ejecutar controlador y normalizar a Response (SIN return temprano)
            $response = \call_user_func_array($controller, $arguments);
            $response = $response instanceof Response ? $response : new Response((string) $response);
        } catch (ResourceNotFoundException $e) {
            if (!$catch) { throw $e; }
            $response = new Response('Not Found', 404);
        } catch (\Throwable $e) {
            if (!$catch) { throw $e; }
            $response = new Response('An error occurred', 500);
        }

        // 4) Despachar SIEMPRE el evento de respuesta (por CLASE, sin nombre string)
        $this->dispatcher->dispatch(new ResponseEvent($response, $request));

        return $response;
    }
}
















//Este archivo se encargará de manejar las solicitudes y las respuestas, siguiendo el principio de "Separation of Concerns".