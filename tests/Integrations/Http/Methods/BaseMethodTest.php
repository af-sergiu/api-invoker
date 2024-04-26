<?php declare(strict_types=1);

/**
 * Тестирует связку классов AfSergiu\ApiInvoker\Http\Methods\BaseMethod,
 * AfSergiu\ApiInvoker\Http\Invokers\BaseRequestInvoker
 */

namespace AfSergiu\ApiInvoker\Tests\Integrations\Http\Methods;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\IArrayStructureBuilder;
use AfSergiu\ApiInvoker\Http\Invokers\BaseRequestInvoker;
use AfSergiu\ApiInvoker\Http\Methods\BaseMethod;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers BaseMethod
 */
class BaseMethodTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $requestMock;
    /**
     * @var MockObject
     */
    private $responseMock;
    /**
     * @var MockObject
     */
    private $requestContructorMock;
    /**
     * @var MockObject
     */
    private $beforeMiddlewareInvokerMock;
    /**
     * @var MockObject
     */
    private $afterMiddlewareInvokerMock;
    /**
     * @var MockObject
     */
    private $responseReaderMock;
    /**
     * @var MockObject
     */
    private $exceptionAdapterMock;
    /**
     * @var MockObject
     */
    private $requestInvokerMock;

    protected function setUp(): void
    {
        $this->requestMock = $this->getRequestMock();
        $this->responseMock = $this->getResponseMock();
        $this->requestContructorMock = $this->getRequestConstructorMock($this->requestMock);
        $this->beforeMiddlewareInvokerMock = $this->getBeforeMiddlewareInvokerMock();
        $this->afterMiddlewareInvokerMock = $this->getAfterMiddlewareInvoker();
        $this->responseReaderMock = $this->getResponseReaderMock();
        $this->exceptionAdapterMock = $this->getExceptionAdapterMock();
        $this->requestInvokerMock = $this->getRequestInvokerMock($this->responseMock, $this->exceptionAdapterMock);
    }

    private function getRequestMock(): MockObject
    {
        return $this->createMock(RequestInterface::class);
    }

    private function getResponseMock(): MockObject
    {
        return $this->createMock(ResponseInterface::class);
    }

    private function getRequestConstructorMock(MockObject $requestMock): MockObject
    {
        $mock = $this->getMockBuilder(IRequestConstructor::class)
                     ->getMock();
        $mock->method('create')
            ->willReturn($requestMock);
        return $mock;
    }

    private function getBeforeMiddlewareInvokerMock(): MockObject
    {
        return $this->createMock(IBeforeMiddlewareInvoker::class);
    }

    private function getAfterMiddlewareInvoker(): MockObject
    {
        return $this->createMock(IAfterMiddlewareInvoker::class);
    }

    private function getResponseReaderMock(): MockObject
    {
        return $this->createMock(IResponseReader::class);
    }

    public function testBeforeMiddlewareInvokedWithCorrectRequest(): void
    {
        $this->beforeMiddlewareInvokerMock->expects($this->once())
            ->method('createChain')
            ->with(
                $this->equalTo([])
            );
        $this->beforeMiddlewareInvokerMock->expects($this->once())
            ->method('invokeChain')
            ->with($this->equalTo($this->requestMock));

        $apiMethod = $this->getApiMethodMock(
            $this->requestContructorMock,
            $this->requestInvokerMock,
            $this->beforeMiddlewareInvokerMock,
            $this->afterMiddlewareInvokerMock
        );

        $apiMethod->setParameters([]);
        $apiMethod->call($this->responseReaderMock);
    }

    private function getRequestInvokerMock(MockObject $responseMock, MockObject $exceptionAdapterMock): MockObject
    {
        $mock = $this->getMockBuilder(BaseRequestInvoker::class)
             ->setConstructorArgs(
                 [$exceptionAdapterMock]
             )
             ->getMockForAbstractClass();
        $mock->method('sendRequest')
            ->willReturn($responseMock);
        return $mock;
    }

    private function getExceptionAdapterMock(): MockObject
    {
        $mock = $this->createMock(IExceptionsAdapter::class);
        $mock->method('adapt')
            ->willReturn(new \Exception());
        return $mock;
    }

    private function getApiMethodMock(
        MockObject $requestConstructor,
        MockObject $requestInvoker,
        MockObject $beforeMiddlewareInvoker,
        MockObject $afterMiddlewareInvoker
    ): MockObject {
        return $this->getMockBuilder(BaseMethod::class)
            ->setConstructorArgs(
                [
                    $requestConstructor,
                    $requestInvoker,
                    $beforeMiddlewareInvoker,
                    $afterMiddlewareInvoker
                ]
            )
            ->getMockForAbstractClass();
    }

    public function testInvokerSendRequestWithCorrectRequest(): void
    {
        $this->requestInvokerMock->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->equalTo($this->requestMock)
            );

        $apiMethod = $this->getApiMethodMock(
            $this->requestContructorMock,
            $this->requestInvokerMock,
            $this->beforeMiddlewareInvokerMock,
            $this->afterMiddlewareInvokerMock
        );

        $apiMethod->setParameters([]);
        $apiMethod->call($this->responseReaderMock);
    }

    public function testArrayParametersSetToRequest(): void
    {
        $requestParameters = ['param1' => 'value1'];
        $apiMethod = $this->getApiMethodMock(
            $this->requestContructorMock,
            $this->requestInvokerMock,
            $this->beforeMiddlewareInvokerMock,
            $this->afterMiddlewareInvokerMock
        );

        $this->requestContructorMock->expects($this->once())
            ->method('create')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->equalTo($requestParameters)
            );

        $apiMethod->setParameters($requestParameters);
        $apiMethod->call($this->responseReaderMock);
    }

    public function testBuldedParametersSetToRequest(): void
    {
        $requestParameters = ['param1' => 'value1'];
        $arrayBuilderMock = $this->getArrayBuilderMock($requestParameters);
        $apiMethod = $this->getApiMethodMock(
            $this->requestContructorMock,
            $this->requestInvokerMock,
            $this->beforeMiddlewareInvokerMock,
            $this->afterMiddlewareInvokerMock
        );

        $arrayBuilderMock->expects($this->once())
            ->method('build');
        $this->requestContructorMock->expects($this->once())
            ->method('create')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->equalTo($requestParameters)
            );

        $apiMethod->setParameters($arrayBuilderMock);
        $apiMethod->call($this->responseReaderMock);
    }

    private function getArrayBuilderMock(array $requestParameters): MockObject
    {
        $mock = $this->createMock(IArrayStructureBuilder::class);
        $mock->method('build')
        ->willReturn($requestParameters);
        return $mock;
    }

    public function testInvokerCallExceptionAdapterInExceptionThrow(): void
    {
        $this->expectException(\Throwable::class);
        $invokerException = new \Exception("Test invoker exception");
        $this->exceptionAdapterMock->expects($this->once())
            ->method('adapt')
            ->with(
                $this->equalTo($invokerException)
            );

        $this->setThrowExceptionInSendInvokerMock($this->requestInvokerMock, $invokerException);
        $apiMethod = $this->getApiMethodMock(
            $this->requestContructorMock,
            $this->requestInvokerMock,
            $this->beforeMiddlewareInvokerMock,
            $this->afterMiddlewareInvokerMock
        );

        $apiMethod->setParameters([]);
        $apiMethod->call($this->responseReaderMock);
    }

    private function setThrowExceptionInSendInvokerMock(MockObject $requestInvokerMock, \Throwable $exception): void
    {
        $requestInvokerMock->method('sendRequest')
            ->willThrowException($exception);
    }

    public function testAfterMiddlewareInvokedWithCorrectRequest(): void
    {
        $this->afterMiddlewareInvokerMock->expects($this->once())
            ->method('createChain')
            ->with(
                $this->equalTo([])
            );
        $this->afterMiddlewareInvokerMock->expects($this->once())
            ->method('invokeChain')
            ->with(
                $this->equalTo($this->responseMock),
                $this->equalTo($this->requestMock)
            );

        $apiMethod = $this->getApiMethodMock(
            $this->requestContructorMock,
            $this->requestInvokerMock,
            $this->beforeMiddlewareInvokerMock,
            $this->afterMiddlewareInvokerMock
        );

        $apiMethod->setParameters([]);
        $apiMethod->call($this->responseReaderMock);
    }

    public function testResponseWasRead(): void
    {
        $this->responseReaderMock->expects($this->once())
            ->method('read')
            ->with(
                $this->equalTo($this->responseMock)
            );
        $apiMethod = $this->getApiMethodMock(
            $this->requestContructorMock,
            $this->requestInvokerMock,
            $this->beforeMiddlewareInvokerMock,
            $this->afterMiddlewareInvokerMock
        );

        $apiMethod->setParameters([]);
        $apiMethod->call($this->responseReaderMock);
    }
}
