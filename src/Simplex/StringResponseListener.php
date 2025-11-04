<?php
// src/Simplex/StringResponseListener.php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class StringResponseListener implements EventSubscriberInterface
{
    public function onView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if (is_string($result)) {
            $event->setResponse(new Response($result));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::VIEW => 'onView'];
    }
}

//Permite que un controlador devuelva string y lo convierte a Response en kernel.view.