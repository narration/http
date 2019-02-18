<?php

declare(strict_types=1);

namespace Narration\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\HttpHandlerRunner\RequestHandlerRunner;

final class Kernel
{
    /**
     * @var \Psr\Http\Server\RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * Kernel constructor.
     *
     * @param  \Psr\Http\Server\RequestHandlerInterface $requestHandler
     */
    public function __construct(RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param  \Psr\Http\Server\RequestHandlerInterface $requestHandler
     *
     * @return \Narration\Http\Kernel
     */
    public static function using(RequestHandlerInterface $requestHandler): self
    {
        return new self($requestHandler);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     */
    public function dispatch(ServerRequestInterface $serverRequest): void
    {
        $runner = new RequestHandlerRunner($this->requestHandler, new SapiEmitter(), function () use ($serverRequest) {
            return $serverRequest;
        }, function (Throwable $exception) {
            throw $exception;
        });

        $runner->run();
    }
}
