<?php declare(strict_types=1);

/**
 * Тестирует связку классов AfSergiu\ApiInvoker\Http\Invokers\GuzzleInvoker и
 * AfSergiu\ApiInvoker\Http\Invokers\GuzzleExceptionsAdapter
 * Тест пока работает некорректно
 */

namespace AfSergiu\ApiInvoker\Tests\Integrations\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Http\Exceptions\ClientException;
use AfSergiu\ApiInvoker\Http\Exceptions\NetworkException;
use AfSergiu\ApiInvoker\Http\Exceptions\ServerAccessException;
use AfSergiu\ApiInvoker\Http\Exceptions\ServerException;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleExceptionsAdapter;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleInvoker;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
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

    private function createExceptionAdapter(): IExceptionsAdapter
    {
        return new GuzzleExceptionsAdapter();
    }

    public function testServerExceptionThrowBy500Codes(): void
    {
        $this->expectException(ServerException::class);

        $handlerStack = $this->getStackHandler([new Response(500, [], 'body')]);
        $client = $this->createGuzzleClient($handlerStack);
        $invoker = $this->createGuzzleInvoker($client, $this->exceptionAdapter);

        $invoker->invoke($this->request);
    }

    private function getStackHandler(array $stack): HandlerStack
    {
        $handlerStack = MockHandler::createWithMiddleware($stack);
        $handlerStack->push(Middleware::httpErrors());
        return $handlerStack;
    }

    private function createGuzzleClient(HandlerStack $handlerStack): Client
    {
        return new Client([
            'handler' => $handlerStack,
            'http_errors' => true
        ]);
    }

    private function createGuzzleInvoker(Client $client, IExceptionsAdapter $exceptionsAdapter): IRequestInvoker
    {
        return new GuzzleInvoker($client, $exceptionsAdapter);
    }

    public function testClientExceptionThrowBy400Codes(): void
    {
        $this->expectException(ClientException::class);

        $handlerStack = $this->getStackHandler([new Response(400, [], 'body')]);
        $client = $this->createGuzzleClient($handlerStack);
        $invoker = $this->createGuzzleInvoker($client, $this->exceptionAdapter);

        $invoker->invoke($this->request);
    }

    public function testServerAccessExceptionThrowBy401(): void
    {
        $this->expectException(ServerAccessException::class);

        $handlerStack = $this->getStackHandler([new Response(401, [], 'body')]);
        $client = $this->createGuzzleClient($handlerStack);
        $invoker = $this->createGuzzleInvoker($client, $this->exceptionAdapter);

        $invoker->invoke($this->request);
    }

    public function testServerAccessExceptionThrowBy403(): void
    {
        $this->expectException(ServerAccessException::class);

        $handlerStack = $this->getStackHandler([new Response(403, [], 'body')]);
        $client = $this->createGuzzleClient($handlerStack);
        $invoker = $this->createGuzzleInvoker($client, $this->exceptionAdapter);

        $invoker->invoke($this->request);
    }

    public function testServerAccessExceptionThrowBy407(): void
    {
        $this->expectException(ServerAccessException::class);

        $handlerStack = $this->getStackHandler([new Response(407, [], 'body')]);
        $client = $this->createGuzzleClient($handlerStack);
        $invoker = $this->createGuzzleInvoker($client, $this->exceptionAdapter);

        $invoker->invoke($this->request);
    }

    public function testNetworkExceptionThrowByDisconnect(): void
    {
        $this->expectException(NetworkException::class);

        $handlerStack = $this->getStackHandler([
            new ConnectException("Disconnect!", new Request('GET', 'uri'))
        ]);
        $client = $this->createGuzzleClient($handlerStack);
        $invoker = $this->createGuzzleInvoker($client, $this->exceptionAdapter);

        $invoker->invoke($this->request);
    }
}
