<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use AfSergiu\ApiInvoker\Exceptions\ClientException;
use AfSergiu\ApiInvoker\Exceptions\NetworkException;
use AfSergiu\ApiInvoker\Exceptions\ServerException;

abstract class BaseRequestInvoker implements IRequestInvoker
{
    private ResponseInterface $response;
    private IExceptionsAdapter $exceptionsAdapter;

    public function __construct(IExceptionsAdapter $exceptionsAdapter)
    {
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
            return $this->response = $this->sendRequest($request);
        } catch (\Throwable $e) {
            throw $this->exceptionsAdapter->adapt($e);
        }
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    abstract protected function sendRequest(RequestInterface $request): ResponseInterface;
}
