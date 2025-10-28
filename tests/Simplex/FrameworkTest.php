<?php
namespace Simplex\Tests;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Calendar\Controller\LeapYearController;
use Symfony\Component\HttpFoundation\Response;

final class FrameworkTest extends TestCase
{
    public function testNotFoundHandling(): void
    {
        $dispatcher = new EventDispatcher();

        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher->method('getContext')->willReturn(new RequestContext());
        $matcher->method('match')->willThrowException(new ResourceNotFoundException());

        $framework = new Framework($dispatcher, $matcher, new ControllerResolver(), new ArgumentResolver());
        $response  = $framework->handle(new Request());

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testErrorHandling(): void
    {
        $dispatcher = new EventDispatcher();

        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher->method('getContext')->willReturn(new RequestContext());
        $matcher->method('match')->willReturn([
            '_controller' => function () {
                throw new \RuntimeException('boom');
            },
        ]);

        $framework = new Framework($dispatcher, $matcher, new ControllerResolver(), new ArgumentResolver());
        $response  = $framework->handle(new Request());

        $this->assertSame(500, $response->getStatusCode());
    }

    public function testControllerResponse(): void
    {
        $dispatcher = new EventDispatcher();

        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher->method('getContext')->willReturn(new RequestContext());
        $matcher->method('match')->willReturn([
            '_route'      => 'is_leap_year/{year}',
            'year'        => 2000,
            '_controller' => [new LeapYearController(), 'index'],
        ]);

        $framework = new Framework($dispatcher, $matcher, new ControllerResolver(), new ArgumentResolver());
        $response  = $framework->handle(new Request());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Yep, this is a leap year!', $response->getContent());
        // por si querés afirmar que siga siendo Response
        $this->assertInstanceOf(Response::class, $response);
    }
}
