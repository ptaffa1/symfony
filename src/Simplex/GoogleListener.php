<?php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class GoogleListener implements EventSubscriberInterface
{
    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if ($response->isRedirection()
            || ($response->headers->has('Content-Type') && !str_contains((string) $response->headers->get('Content-Type'), 'html'))
            || 'html' !== $event->getRequest()->getRequestFormat()
        ) {
            return;
        }

        $response->setContent($response->getContent().'GA CODE');
    }

    public static function getSubscribedEvents(): array
    {
        // Suscripción por CLASE (coincide con el dispatch anterior)
        return [ ResponseEvent::class => 'onResponse' ];
    }
}
