<?php declare(strict_types=1);

/**
 * Тестирует связку классов AfSergiu\ApiInvoker\Http\Methods\BaseMethod,
 * AfSergiu\ApiInvoker\Http\Invokers\BaseRequestInvoker
 */

namespace AfSergiu\ApiInvoker\Tests\Integrations\Http\Methods;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Http\Invokers\BaseRequestInvoker;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
    private $beforeMiddlewareInvoker;
    /**
     * @var MockObject
     */
    private $afterMiddlewareInvoker;
    /**
     * @var MockObject
     */
    private $responseReaderMock;

    protected function setUp(): void
    {
        $this->requestMock = $this->getRequestMock();
        $this->responseMock = $this->getResponseMock();
        $this->requestContructorMock = $this->getRequestConstructorMock($this->requestMock);
        $this->beforeMiddlewareInvoker = $this->getBeforeMiddlewareInvokerMock();
        $this->afterMiddlewareInvoker = $this->getAfterMiddlewareInvoker();
        $this->responseReaderMock = $this->getResponseReaderMock();
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
                    ->addMethods(['create'])
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
        $requestInvoker = $this->getRequestInvokerMock($this->responseMock);
        $apiMethod = $this->getApiMethodMock(
            $this->requestContructorMock,
            $requestInvoker,
            $this->beforeMiddlewareInvoker,
            $this->afterMiddlewareInvoker
        );

        $apiMethod->setParameters([]);
        $apiMethod->call($this->responseReaderMock);

        $this->beforeMiddlewareInvoker->expects(1)
            ->method('invokeChain')
            ->with($this->requestMock);
    }

    private function getRequestInvokerMock(MockObject $responseMock): MockObject
    {
        $mock = $this->getMockForAbstractClass(BaseRequestInvoker::class);
        $mock->method('sendRequest')
            ->willReturn($responseMock);
        return $mock;
    }

    private function getApiMethodMock(
        MockObject $requestConstructor,
        MockObject $requestInvoker,
        MockObject $beforeMiddlewareInvoker,
        MockObject $afterMiddlewareInvoker
    ): MockObject {

    }
}
