<?php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ContentLengthListener implements EventSubscriberInterface
{
    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $headers  = $response->headers;

        if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
            $headers->set('Content-Length', (string) strlen($response->getContent()));
        }
    }

    public static function getSubscribedEvents(): array
    {
        // Ejecutar al final
       return [KernelEvents::RESPONSE => ['onResponse', -255]];
    }
}
