<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Factories\Http\Middleware;

use AfSergiu\ApiInvoker\Contracts\Factories\Http\Middleware\IMiddlewareInvokerFactory;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IMiddlewareChainBuilder;
use AfSergiu\ApiInvoker\Http\Middleware\AfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Middleware\MiddlewareChainBuilder;
use Psr\Container\ContainerInterface;

class AfterMiddlewareInvokerFactory implements IMiddlewareInvokerFactory
{
    /**
     * @param ContainerInterface $container
     * @return IAfterMiddlewareInvoker
     */
    public function create(ContainerInterface $container)
    {
        $chainBuilder = $this->createChainBuilder($container);
        return new AfterMiddlewareInvoker($chainBuilder);
    }

    private function createChainBuilder(ContainerInterface $container): IMiddlewareChainBuilder
    {
        return new MiddlewareChainBuilder($container);
    }
}
