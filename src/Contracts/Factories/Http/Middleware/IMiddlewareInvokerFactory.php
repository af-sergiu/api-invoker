<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Factories\Http\Middleware;

use Psr\Container\ContainerInterface;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;

interface IMiddlewareInvokerFactory
{
    /**
     * @param ContainerInterface $container
     * @return IAfterMiddlewareInvoker|IBeforeMiddlewareInvoker
     */
    public function create(ContainerInterface $container);
}
