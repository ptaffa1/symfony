<?php
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class CachingListener implements EventSubscriberInterface
{
    public function onResponse(ResponseEvent $event): void
    {
        if (($event->getRequest()->attributes->get('_route') ?? null) !== 'leap_year') {
            return;
        }
        $res = $event->getResponse();
        $res->setPublic();
        $res->setTtl(10); // cache compartida por 10s
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE => 'onResponse'];
    }
}
