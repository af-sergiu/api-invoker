<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Middleware;


use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IMiddlewareChainBuilder;

abstract class MiddlewareInvoker
{
    /**
     * @var \Closure
     */
    protected $middlewareChain;
    /**
     * @var IMiddlewareChainBuilder
     */
    private $chainBuilder;

    public function __construct(IMiddlewareChainBuilder $chainBuilder)
    {
        $this->chainBuilder = $chainBuilder;
    }

    /**
     * @param array<int, string|\Closure> $middleware
     * @return mixed
     */
    public function createChain(array $middleware): void
    {
        $this->middlewareChain = $this->chainBuilder->createChain($middleware);
    }
}
