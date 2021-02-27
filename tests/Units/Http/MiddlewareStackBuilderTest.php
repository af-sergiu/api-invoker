<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Tests;

use AfSergiu\ApiInvoker\Http\Middleware\MiddlewareStackBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class MiddlewareStackBuilderTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $container;
    /**
     * @var array
     */
    private $containerMiddlewareEntries = [];
    /**
     * @var MockObject
     */
    private $instanceMiddlewareMock;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->instanceMiddlewareMock = $this->createInstanceMiddlewareMock();
        $this->containerMiddlewareEntries = [
            'middleware1' => $this->instanceMiddlewareMock,
            'middleware2' => $this->instanceMiddlewareMock,
            'middleware3' => $this->instanceMiddlewareMock
        ];
        $this->container = $this->createContainerWithEntries();
    }

    public function testChainInvokedAllMiddleware():void
    {
        $instantiatingMiddleware = array_keys($this->containerMiddlewareEntries);
        $middleware = array_merge($instantiatingMiddleware);
        $middlewareArguments = ['argument1', new \stdClass()];

        $stackBuilder = new MiddlewareStackBuilder($this->container);
        $closureChain = $stackBuilder->createChain($middleware);

        $this->instanceMiddlewareMock->expects($this->exactly(count($instantiatingMiddleware)))
            ->method('handle');
        $closureChain(...$middlewareArguments);
    }

    public function testChainInvokedWithCorrectArguments():void
    {
        $instantiatingMiddleware = array_keys($this->containerMiddlewareEntries);
        $middleware = array_merge($instantiatingMiddleware);
        $middlewareArguments = ['argument1', new \stdClass()];

        $stackBuilder = new MiddlewareStackBuilder($this->container);
        $closureChain = $stackBuilder->createChain($middleware);

        $this->instanceMiddlewareMock->expects($this->exactly(count($instantiatingMiddleware)))
            ->method('handle')
            ->with(
                $this->isInstanceOf(\Closure::class),
                $this->equalTo($middlewareArguments[0]),
                $this->equalTo($middlewareArguments[1])
            );
        $closureChain(...$middlewareArguments);
    }

    private function createInstanceMiddlewareMock(): MockObject
    {
        $mock = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['handle'])
            ->getMock();
        $mock->method('handle')
            ->willReturn($this->returnCallback(function ()
                {
                    $nextHandlerArg = func_get_arg(0);
                    $arguments = array_slice(func_get_args(), 1);
                    return $nextHandlerArg(...$arguments);
                })
            );
        return $mock;
    }

//    private function createCallableMiddlewareMock(): MockObject
//    {
//        $mock = $this->getMockBuilder(\stdClass::class)
//            ->addMethods(['__invoke'])
//            ->getMock();
//        $mock->method('__invoke')
//            ->willReturn($this->callback(function ()
//            {
//                $nextHandlerArg = func_get_arg(0);
//                $arguments = array_slice(func_get_args(), 1);
//                return $nextHandlerArg($arguments);
//            })
//            );
//        return $mock;
//    }

    private function createContainerWithEntries(): MockObject {
        $mock = $this->getMockBuilder(ContainerInterface::class)
            ->getMock();
        $mock->method('get')
            ->willReturn($this->returnCallback(function () {
                $key = func_get_arg(0);
                return $this->containerMiddlewareEntries[$key];
            }));
        return $mock;
    }
}
