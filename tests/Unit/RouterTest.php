<?php

namespace Tests\Unit;

use Narration\Http\Router;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\ServerRequestFactory;

final class RouterTest extends TestCase
{
    public function testDispatchClosures(): void
    {
        $router = new Router(new Container);

        $router->get('/bar', function () {
            return ['foo', 'bar'];
        });

        $response = $router->handle((new ServerRequestFactory())->createServerRequest('GET', '/bar'));
        self::assertEquals($response->getBody(), json_encode(['foo', 'bar']));
        self::assertEquals($response->getStatusCode(), 200);

        $router = new Router(new Container);
        $router->post('/foo', function () {
            throw new \InvalidArgumentException('zadza');
        });
        $response = $router->handle((new ServerRequestFactory())->createServerRequest('POST', '/foo'));
        self::assertEquals($response->getStatusCode(), 500);
    }
}

final class Container implements ContainerInterface
{
    public function get($id)
    {
        return 'foo';
    }

    public function has($id): bool
    {
        return true;
    }
}
