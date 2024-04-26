<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Tests\Systems\Http\Middleware;

use AfSergiu\ApiInvoker\Factories\Http\Middleware\AfterMiddlewareInvokerFactory;
use AfSergiu\ApiInvoker\Http\Middleware\AfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Tests\Factories\Mock\Http\ResponseMockFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers AfterMiddlewareInvoker
 */
class AfterMiddlewareInvokerTest extends MiddlewareInvoker
{
    private MockObject|ResponseInterface $response;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->response = $this->createResponseMock();
    }

    private function createResponseMock(): MockObject
    {
        return (new ResponseMockFactory($this))->create();
    }

    public function testInvokerCallAllMiddleware(): void
    {
        $middlewareInvoker = $this->createMiddlewareInvoker();
        $middleware = $this->getMiddlewareList();
        $middlewareInvoker->createChain($middleware);

        $this->expectEveryMiddlewareCall($middleware);
        $middlewareInvoker->invokeChain($this->response, $this->request);
    }

    private function createMiddlewareInvoker()
    {
        return (new AfterMiddlewareInvokerFactory())->create($this->container);
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
        $middlewareInvoker->invokeChain($this->response, $this->request);
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
                    $this->equalTo($this->response),
                    $this->equalTo($this->request),
                    $this->isInstanceOf(\Closure::class)
                );
        }
    }

    public function testCallZeroMiddlewareStackReturnCorrectValue(): void
    {
        $middlewareInvoker = $this->createMiddlewareInvoker();
        $middlewareInvoker->createChain([]);

        $result = $middlewareInvoker->invokeChain($this->response, $this->request);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

}
