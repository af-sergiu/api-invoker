<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Factories\Http;

use AfSergiu\ApiInvoker\Contracts\Factories\Http\IApiMethodFactory;
use AfSergiu\ApiInvoker\Contracts\Http\IApiMethod;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IMiddlewareChainBuilder;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleInvoker;
use AfSergiu\ApiInvoker\Http\Middleware\AfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Middleware\BeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Middleware\MiddlewareChainBuilder;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

abstract class ApiMethodFactory implements IApiMethodFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(string $className): IApiMethod
    {
        return new $className(
            $this->createApiRequestConstructor(),
            $this->createApiInvoker(),
            $this->createBeforeMiddlewareInvoker(),
            $this->createAfterMiddlewareInvoker()
        );
    }

    abstract protected function createApiRequestConstructor(): IRequestConstructor;

    private function createApiInvoker(): IRequestInvoker
    {
        return new GuzzleInvoker(new Client());
    }

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
