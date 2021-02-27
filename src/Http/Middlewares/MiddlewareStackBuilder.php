<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Middlewares;

use AfSergiu\ApiInvoker\Contracts\IMiddlewareStackBuilder;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MiddlewareStackBuilder implements IMiddlewareStackBuilder
{
    /**
     * @var ContainerInterface
     */
    private $container;
    private $middlewareList = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createChain(array $middleware): \Closure
    {
        $this->middlewareList = $middleware;
        if (count($middleware)) {
            return $this->createRecursiveChain();
        } else {
            return $this->getLastCallback();
        }
    }

    private function createRecursiveChain(int $idx=0): \Closure
    {
        $nextHandler = $this->getNextHandler($idx);
        $middleware = $this->resolveMiddleware($this->middlewareList[$idx]);
        return function (...$arguments) use ($middleware, $nextHandler)
        {
            return $this->invokeMiddleware($middleware, $nextHandler, ...$arguments);
        };
    }

    private function getNextHandler(int $idx): \Closure
    {
        $nextidx = $idx + 1;
        if (isset($this->middlewareList[$nextidx])) {
            $nextHandler = $this->createRecursiveChain($nextidx);
        } else {
            $nextHandler = $this->getLastCallback();
        }
        return $nextHandler;
    }

    private function getLastCallback(...$middlewareArguments): \Closure
    {
        return function (...$middlewareArguments)
        {
            return func_get_arg(0);
        };
    }

    /**
     * @param $middleware
     * @return mixed
     */
    private function resolveMiddleware($middleware)
    {
        if (is_callable($middleware) && function_exists($middleware)) {
            return $middleware;
        } elseif (is_string($middleware)) {
            return $this->instantiateMiddleware($middleware);
        } else {
            throw new \RuntimeException("Incorrect middlware type");
        }
    }

    private function instantiateMiddleware(string $middleware): object
    {
        try {
            $instance = $this->container->get($middleware);
            $reflection = new \ReflectionClass($instance);
            if ($reflection->hasMethod('handle')) {
                return $instance;
            } else {
                throw new \RuntimeException("Middleware class must have hahdle method");
            }
        } catch (NotFoundExceptionInterface $exception) {
            throw new \RuntimeException("Not found middleware class");
        } catch (\ReflectionException $exception) {
            throw new \RuntimeException("Middleware is not a class name");
        }
    }

    private function invokeMiddleware($middleware, $nextHandler, ...$arguments)
    {
        if (is_object($middleware)) {
            $middleware->handle($nextHandler, ...$arguments);
        } else {
            $middleware->__invoke($nextHandler, ...$arguments);
        }
    }
}
