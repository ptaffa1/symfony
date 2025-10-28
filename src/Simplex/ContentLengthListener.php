<?php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
        return [ ResponseEvent::class => ['onResponse', -255] ];
    }
}
