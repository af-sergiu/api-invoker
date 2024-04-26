<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Factories\Http;

use AfSergiu\ApiInvoker\Contracts\Factories\Http\IMethodFactory;
use AfSergiu\ApiInvoker\Contracts\Http\IMethod;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IMiddlewareChainBuilder;
use AfSergiu\ApiInvoker\Http\Constructors\RequestConstructor;
use AfSergiu\ApiInvoker\Http\Middleware\AfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Middleware\BeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Middleware\MiddlewareChainBuilder;
use Psr\Container\ContainerInterface;

abstract class MethodFactory implements IMethodFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(string $className): IMethod
    {
        return new $className(
            $this->createApiRequestConstructor(),
            $this->createApiInvoker(),
            $this->createBeforeMiddlewareInvoker(),
            $this->createAfterMiddlewareInvoker()
        );
    }

    private function createApiRequestConstructor(): IRequestConstructor
    {
        $requestConstructor = new RequestConstructor();
        $requestConstructor->setBuilder(
            $this->createRequestBuilder()
        );
        return $requestConstructor;
    }

    abstract protected function createRequestBuilder(): IRequestBuilder;

    abstract protected function createApiInvoker(): IRequestInvoker;

    private function createBeforeMiddlewareInvoker(): IBeforeMiddlewareInvoker
    {
        return new BeforeMiddlewareInvoker(
            $this->createMiddlewareChainBuilder()
        );
    }

    private function createMiddlewareChainBuilder(): IMiddlewareChainBuilder
    {
        return new MiddlewareChainBuilder($this->container);
    }

    private function createAfterMiddlewareInvoker(): IAfterMiddlewareInvoker
    {
        return new AfterMiddlewareInvoker(
            $this->createMiddlewareChainBuilder()
        );
    }
}
