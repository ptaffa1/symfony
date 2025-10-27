<?php
declare(strict_types=1);

namespace Simplex\Tests;

use Calendar\Controller\LeapYearController;
use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
//Importa clases y mocks/dobles necesarios.

final class FrameworkTest extends TestCase
{
    public function testNotFoundHandling(): void
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testErrorHandling(): void
    {
        $framework = $this->getFrameworkForException(new \RuntimeException('boom'));

        $response = $framework->handle(new Request());

        $this->assertSame(500, $response->getStatusCode());
    }

    public function testControllerResponse(): void
    {
        // Matcher “feliz”: hace match y retorna controlador real
        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher->expects($this->once())
            ->method('match')
            ->willReturn([
                '_route' => 'is_leap_year/{year}',
                'year' => 2000,
                '_controller' => [new LeapYearController(), 'index'],
            ]);
        $matcher->expects($this->once())
            ->method('getContext')
            ->willReturn(new RequestContext());

        // Resolvers reales (no mocks) para este test
        $controllerResolver = new ControllerResolver();
        $argumentResolver   = new ArgumentResolver();

        $framework = new Framework($matcher, $controllerResolver, $argumentResolver);

        $response = $framework->handle(new Request());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Yep, this is a leap year!', $response->getContent());
    }

    private function getFrameworkForException(\Throwable $exception): Framework
    {
        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher->expects($this->once())
            ->method('match')
            ->willThrowException($exception);
        $matcher->expects($this->once())
            ->method('getContext')
            ->willReturn(new RequestContext());

        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver   = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }
}
