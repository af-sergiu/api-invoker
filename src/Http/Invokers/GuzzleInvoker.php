<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
use AfSergiu\ApiInvoker\Http\Exceptions\ClientException;
use AfSergiu\ApiInvoker\Http\Exceptions\NetworkException;
use AfSergiu\ApiInvoker\Http\Exceptions\ServerException;
use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleInvoker implements IRequestInvoker
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var IExceptionsAdapter
     */
    private $exceptionsAdapter;

    public function __construct(Client $httpClient, IExceptionsAdapter $exceptionsAdapter)
    {
        $this->httpClient = $httpClient;
        $this->exceptionsAdapter = $exceptionsAdapter;
    }

    /**
     * @throws ServerException
     * @throws ClientException
     * @throws NetworkException
     * @throws \Throwable
     */
    public function invoke(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->httpClient->sendRequest($request);
        } catch (\Throwable $e) {
            throw $this->exceptionsAdapter->adapt($e);
        }
    }

    /**
     * @throws \RuntimeException
     */
    public function read(IResponseReader $reader)
    {
        if ($this->response instanceof ResponseInterface) {
            return $reader->read($this->response);
        } else {
            throw new \RuntimeException('Request was not invoked');
        }
    }
}
