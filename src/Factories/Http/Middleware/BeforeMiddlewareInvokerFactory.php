<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Factories\Http\Middleware;

use AfSergiu\ApiInvoker\Contracts\Factories\Http\Middleware\IMiddlewareInvokerFactory;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IMiddlewareChainBuilder;
use AfSergiu\ApiInvoker\Http\Middleware\BeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Middleware\MiddlewareChainBuilder;
use Psr\Container\ContainerInterface;

class BeforeMiddlewareInvokerFactory implements IMiddlewareInvokerFactory
{
    /**
     * @param ContainerInterface $container
     * @return IBeforeMiddlewareInvoker
     */
    public function create(ContainerInterface $container)
    {
        $chainBuilder = $this->createChainBuilder($container);
        return new BeforeMiddlewareInvoker($chainBuilder);
    }

    private function createChainBuilder(ContainerInterface $container): IMiddlewareChainBuilder
    {
        return new MiddlewareChainBuilder($container);
    }
}
