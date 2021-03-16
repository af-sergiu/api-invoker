<?php declare(strict_types=1);

/**
 * Тестирует связку классов AfSergiu\ApiInvoker\Http\Invokers\GuzzleInvoker и
 * AfSergiu\ApiInvoker\Http\Invokers\GuzzleExceptionsAdapter
 * Тест пока работает некорректно
 */

namespace AfSergiu\ApiInvoker\Tests\Integrations\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Http\Exceptions\ServerException;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleExceptionsAdapter;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleInvoker;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class GuzzleInvokerTest extends TestCase
{
    /**
     * @type RequestInterface
     */
    private $request;
    /**
     * @var IExceptionsAdapter
     */
    private $exceptionAdapter;

    protected function setUp(): void
    {
        $this->request = new Request('GET', 'https://domain');
        $this->exceptionAdapter = $this->createExceptionAdapter();
    }

    public function testServerExceptionThrowBy500Codes(): void
    {
        $this->expectException(ServerException::class);

        $mockHandler = new MockHandler([new Response(500), Middleware::httpErrors()]);
        $client = $this->createGuzzleClient($mockHandler);
        $invoker = $this->createGuzzleInvoker($client, $this->exceptionAdapter);

        $invoker->invoke($this->request);
    }

    private function createExceptionAdapter(): IExceptionsAdapter
    {
        return new GuzzleExceptionsAdapter();
    }

    private function createGuzzleClient(MockHandler $mockHandler): ClientInterface
    {
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->push(Middleware::httpErrors());
        return new Client([
            'handler' => $handlerStack,
            'http_errors' => true
        ]);
    }

    private function createGuzzleInvoker(ClientInterface $client, IExceptionsAdapter $exceptionsAdapter): IRequestInvoker
    {
        return new GuzzleInvoker($client, $exceptionsAdapter);
    }
}
