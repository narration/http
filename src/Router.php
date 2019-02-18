<?php

declare(strict_types=1);

namespace Narration\Http;

use League\Route\Middleware\MiddlewareAwareInterface;
use League\Route\Middleware\MiddlewareAwareTrait;
use League\Route\Route;
use League\Route\RouteCollectionTrait;
use League\Route\Router as BaseRouter;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Router implements RequestHandlerInterface, MiddlewareAwareInterface
{
    use RouteCollectionTrait, MiddlewareAwareTrait;

    /**
     * @var \League\Route\Router
     */
    private $baseRouter;

    /**
     * Router constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $responseFactory = new \Zend\Diactoros\ResponseFactory;
        $strategy = new \League\Route\Strategy\JsonStrategy($responseFactory);
        $this->baseRouter = (new BaseRouter)->setStrategy($strategy->setContainer($container));
    }

    /**
     * @param  string $method
     * @param  string $path
     * @param  callable|string $handler
     *
     * @return \League\Route\Route
     */
    public function map(string $method, string $path, $handler): Route
    {
        return $this->baseRouter->map($method, $path, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->baseRouter->dispatch($request);
    }
}
