<?php
namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

final class ResponseEvent extends Event
{
    public function __construct(
        private Response $response,
        private Request $request,
    ) {}

    public function getResponse(): Response { return $this->response; }
    public function getRequest(): Request   { return $this->request; }
}



/**
 * Evento que lleva el Response y la Request para que los listeners
 * puedan inspeccionarlos/modificarlos.
 */
