<?php

declare(strict_types=1);

/**
 * This file is part of Narration Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

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
     * @param \Psr\Http\Server\RequestHandlerInterface $requestHandler
     */
    public function __construct(RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     */
    public function handle(ServerRequestInterface $serverRequest): void
    {
        $runner = new RequestHandlerRunner($this->requestHandler, new SapiEmitter(), function () use ($serverRequest) {
            return $serverRequest;
        }, function (Throwable $exception) {
            throw $exception;
        });

        $runner->run();
    }
}
