<?php
namespace Simplex\Tests;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Simplex\ContentLengthListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;


final class DispatcherTest extends TestCase
{
    public function testContentLengthIsAdded(): void
    {
       $dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
        $dispatcher->addSubscriber(new \Simplex\ContentLengthListener());

        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher->method('getContext')->willReturn(new RequestContext());
        $matcher->method('match')->willReturn([
            '_controller' => fn () => new Response('<html>Hi</html>'),
        ]);

        $framework = new \Simplex\Framework(
        $dispatcher,
            $matcher,
        new \Symfony\Component\HttpKernel\Controller\ControllerResolver(),
        new \Symfony\Component\HttpKernel\Controller\ArgumentResolver()
        );
        $res = $framework->handle(new Request());

        // debug opcional si falla:
        // var_dump($res->headers->all());

        $this->assertTrue($res->headers->has('Content-Length'));
        $this->assertSame((string) strlen($res->getContent()), $res->headers->get('Content-Length'));
    }
}
