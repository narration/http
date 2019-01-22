<?php

namespace Narration\Http;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

final class Request
{
    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function capture(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals();
    }
}
