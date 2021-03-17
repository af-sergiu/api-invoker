<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class GuzzleInvoker extends BaseRequestInvoker
{
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(Client $httpClient, IExceptionsAdapter $exceptionsAdapter)
    {
        parent::__construct($exceptionsAdapter);
        $this->httpClient = $httpClient;
    }

    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->send($request, $this->getGuzzleRequestConfig());
    }

    private function getGuzzleRequestConfig(): array
    {
        return array_merge([], $this->getThrowHttpErrorsConfig());
    }

    private function getThrowHttpErrorsConfig(): array
    {
        return ['http_errors' => true];
    }
}
