<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public function home(Request $request): Response
    {
        return new Response('Welcome to Miny symfony');
    }

    public function hello(Request $request, string $name): Response
    {
        return new Response('Hello ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
    }
}
