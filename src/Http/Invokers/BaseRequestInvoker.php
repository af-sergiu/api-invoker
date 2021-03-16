<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use AfSergiu\ApiInvoker\Http\Exceptions\ClientException;
use AfSergiu\ApiInvoker\Http\Exceptions\NetworkException;
use AfSergiu\ApiInvoker\Http\Exceptions\ServerException;

abstract class BaseRequestInvoker implements IRequestInvoker
{
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var IExceptionsAdapter
     */
    private $exceptionsAdapter;

    public function __construct(IExceptionsAdapter $exceptionsAdapter)
    {
        $this->exceptionsAdapter = $exceptionsAdapter;
    }

    /**
     * @throws ServerException
     * @throws ClientException
     * @throws NetworkException
     * @throws \Throwable
     * @param RequestInterface $request
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
