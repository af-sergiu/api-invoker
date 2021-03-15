<?php declare(strict_types=1);

/**
 * Тестирует связку классов AfSergiu\ApiInvoker\Http\Methods\BaseMethod,
 * AfSergiu\ApiInvoker\Http\Invokers\BaseRequestInvoker
 */

namespace AfSergiu\ApiInvoker\Tests\Integrations\Http\Methods;

use PHPUnit\Framework\TestCase;

class BaseMethodTest extends TestCase
{
    public function testBeforeMiddlewareInvokedWithCorrectRequest(): void
    {
        $request = $this->getRequestMock();
        $responseMock = $this->getResponseMock();
        $requestContructorMock = $this->getRequestConstructorMock($request);
        $requestInvoker = $this->getRequestInvokerMock($responseMock);
        $beforeMiddlewareInvoker = $this->getBeforeMiddlewareInvokerMock();
        $afterMiddlewareInvoker = $this->getAfterMiddlewareInvoker();
        $apiMethod = $this->getApiMethodMock(
            $requestContructorMock,
            $requestInvoker,
            $beforeMiddlewareInvoker,
            $afterMiddlewareInvoker
        );
        $responseReaderMock = $this->getResponseReaderMock();

        $apiMethod->setParameters([]);
        $apiMethod->call($responseReaderMock);

        $beforeMiddlewareInvoker->expects(1)
            ->method('invokeChain')
            ->with($request);
    }
}
