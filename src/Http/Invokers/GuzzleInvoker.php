<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
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

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function invoke(RequestInterface $request)
    {
        $this->response = $this->httpClient->sendRequest($request);
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
