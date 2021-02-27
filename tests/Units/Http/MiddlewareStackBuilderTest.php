<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Tests;

use AfSergiu\ApiInvoker\Http\Middlewares\MiddlewareStackBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class MiddlewareStackBuilderTest extends TestCase
{
    public function testChainInvokeAllMiddleware():void
    {
        $instanceMiddlewareMock = $this->createInstanceMiddlewareMock();
        $callableMiddlewareMock = $this->createCallableMiddlewareMock();
        $containerEntries = [
            'middleware1' => $instanceMiddlewareMock,
            'middleware2' => $instanceMiddlewareMock,
            'middleware3' => $instanceMiddlewareMock
        ];
        $instantiatingMiddlewares = array_keys($containerEntries);
        $callableMiddlewares = [
            get_class($callableMiddlewareMock),
            get_class($callableMiddlewareMock)
        ];
        $container = $this->createContainerWithEntries($containerEntries);
        $middlewares = array_merge($instantiatingMiddlewares, $callableMiddlewares);
        $middlewareArguments = [
            'argument1',
            new \stdClass()
        ];

        $stackBuilder = new MiddlewareStackBuilder($container);
        $closureChain = $stackBuilder->createChain($middlewares);
        $closureChain(...$middlewareArguments);

        $instanceMiddlewareMock->expects($this->exactly(count($instantiatingMiddlewares)))
            ->method('handle')
            ->with(
                $this->isInstanceOf(\Closure::class),
                $this->equalTo($middlewareArguments[0]),
                $this->equalTo($middlewareArguments[1])
            );
        $callableMiddlewareMock->expects($this->exactly(count($instantiatingMiddlewares)))
            ->method('__invoke')
            ->with(
                $this->isInstanceOf(\Closure::class),
                $this->equalTo($middlewareArguments[0]),
                $this->equalTo($middlewareArguments[1])
            );
    }

    private function createInstanceMiddlewareMock(): MockObject
    {
        $mock = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['handle'])
            ->getMock();
        $mock->method('handle')
            ->willReturn($this->callback(function ()
                {
                    $nextHandlerArg = func_get_arg(0);
                    $arguments = array_slice(func_get_args(), 1);
                    return $nextHandlerArg($arguments);
                })
            );
        return $mock;
    }

    private function createCallableMiddlewareMock(): MockObject
    {
        $mock = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['__invoke'])
            ->getMock();
        $mock->method('__invoke')
            ->willReturn($this->callback(function ()
            {
                $nextHandlerArg = func_get_arg(0);
                $arguments = array_slice(func_get_args(), 1);
                return $nextHandlerArg($arguments);
            })
            );
        return $mock;
    }

    private function createContainerWithEntries(array $entries): MockObject {
        $mock = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();
        $mock->method('get')
            ->willReturn($this->returnCallback(function () use ($entries) {
                $key = func_get_arg(0);
                return $entries[$key];
            }));
        return $mock;
    }
}
