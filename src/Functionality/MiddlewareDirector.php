<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Functionality;

use AfSergiu\ApiInvoker\Contracts\Http\IMiddleware;
use AfSergiu\ApiInvoker\Contracts\IMiddlewareDirector;
use Psr\Container\ContainerInterface;

abstract class MiddlewareDirector implements IMiddlewareDirector
{
    /**
     * @var array
     */
    private $middlewares = [];
    /**
     * @var \Closure
     */
    private $lastCallable;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function invoke(array $middlewares, \Closure $lastCallable): void
    {
        if (count($middlewares) > 0) {
            $this->middlewares = $middlewares;
            $this->lastCallable = $lastCallable;
            $chain = $this->getCallableChain();
            $this->invokeMiddlewareChain($chain);
        } else {
            $this->invokeMiddlewareChain($this->lastCallable);
        }
    }

    private function getCallableChain(int $currentIdx=0): \Closure
    {
        return function (...$arguments) use ($currentIdx)
        {
            $middleware = $this->instantiateMiddleware($this->middlewares[$currentIdx]);
            if ($currentIdx === array_key_last($this->middlewares)) {
                return $this->invokeMiddleware($middleware, $this->lastCallable);
            } else {
                $nextIdx = $currentIdx + 1;
                return $this->invokeMiddleware($middleware, $this->getCallableChain($nextIdx));
            }
        };
    }

    protected function instantiateMiddleware(string $className): IMiddleware
    {
        return $this->container->get($className);
    }

    abstract protected function invokeMiddlewareChain(\Closure $chain);

    abstract protected function invokeMiddleware(object $middleware, \Closure $next);
}
