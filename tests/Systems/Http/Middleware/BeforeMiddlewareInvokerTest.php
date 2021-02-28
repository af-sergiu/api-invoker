<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Tests\Systems\Http\Middleware;

use AfSergiu\ApiInvoker\Factories\Http\Middleware\BeforeMiddlewareInvokerFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;

class BeforeMiddlewareInvokerTest extends MiddlewareInvoker
{
    public function testInvokerCallAllMiddleware(): void
    {
        $middlewareInvoker = $this->createMiddlewareInvoker();
        $middleware = $this->getMiddlewareList();
        $middlewareInvoker->createChain($middleware);

        $this->expectEveryMiddlewareCall($middleware);
        $middlewareInvoker->invokeChain($this->request);
    }

    private function createMiddlewareInvoker()
    {
        return (new BeforeMiddlewareInvokerFactory())->create($this->container);
    }

    /**
     * @param array<int, string> $middleware
     */
    private function expectEveryMiddlewareCall(array $middleware): void
    {
        foreach ($middleware as $item) {
            /** @var MockObject $middlewareMock */
            $middlewareMock = $this->container->get($item);
            $middlewareMock->expects($this->once())
                ->method('handle');
        }
    }

    public function testInvokerCallMiddlewareWithCorrectArgs(): void
    {
        $middlewareInvoker = $this->createMiddlewareInvoker();
        $middleware = $this->getMiddlewareList();
        $middlewareInvoker->createChain($middleware);

        $this->expectEveryMiddlewareCall($middleware);
        $middlewareInvoker->invokeChain($this->request);
    }

    /**
     * @param array<int, string> $middleware
     */
    private function expectEveryMiddlewareCallWithRequest(array $middleware): void
    {
        foreach ($middleware as $item) {
            /** @var MockObject $middlewareMock */
            $middlewareMock = $this->container->get($item);
            $middlewareMock->expects($this->once())
                ->method('handle')
                ->with(
                    $this->equalTo($this->request),
                    $this->isInstanceOf(\Closure::class)
                );
        }
    }

    public function testCallZeroMiddlewareStackReturnCorrectValue(): void
    {
        $middlewareInvoker = $this->createMiddlewareInvoker();
        $middlewareInvoker->createChain([]);

        $result = $middlewareInvoker->invokeChain($this->request);
        $this->assertInstanceOf(RequestInterface::class, $result);
    }
}
