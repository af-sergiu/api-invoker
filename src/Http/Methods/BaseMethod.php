<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Methods;

use AfSergiu\ApiInvoker\Contracts\Http\IMethod;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\IArrayStructureBuilder;
use AfSergiu\ApiInvoker\Exceptions\BadResponseException;
use AfSergiu\ApiInvoker\Http\Invokers\BaseRequestInvoker;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddleware;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddleware;

abstract class BaseMethod implements IMethod
{
    protected string $httpMethod = 'GET';
    protected string $uri = '';
    protected array $addHeaders=[];
    /**
     * @var array<\Closure|IBeforeMiddleware>
     */
    protected array $beforeMiddleware = [];
    /**
     * @var array<\Closure|IAfterMiddleware>
     */
    protected array $afterMiddleware = [];
    protected mixed $parameters;
    protected IRequestConstructor $requestConstructor;
    private RequestInterface $request;
    private BaseRequestInvoker $requestInvoker;
    private IBeforeMiddlewareInvoker $beforeMiddlewareInvoker;
    private IAfterMiddlewareInvoker $afterMiddlewareInvoker;
    private ResponseInterface $response;

    public function __construct(
        IRequestConstructor $requestConstructor,
        BaseRequestInvoker $requestInvoker,
        IBeforeMiddlewareInvoker $beforeMiddlewareInvoker,
        IAfterMiddlewareInvoker $afterMiddlewareInvoker
    ) {
        $this->requestConstructor = $requestConstructor;
        $this->requestInvoker = $requestInvoker;
        $this->beforeMiddlewareInvoker = $beforeMiddlewareInvoker;
        $this->afterMiddlewareInvoker = $afterMiddlewareInvoker;
    }

    final public function setParameters($parameters): IMethod
    {
        if ($parameters instanceof IArrayStructureBuilder) {
            $this->parameters = $parameters->build();
        } else {
            $this->parameters = $parameters;
        }
        return $this;
    }

    /**
     * @throws \AfSergiu\ApiInvoker\Exceptions\ClientException
     * @throws \AfSergiu\ApiInvoker\Exceptions\NetworkException
     * @throws \AfSergiu\ApiInvoker\Exceptions\ServerException
     * @throws \Throwable
     */
    public function call(IResponseReader $responseReader): mixed
    {
        try {
            $this->request = $this->createRequest();
            $this->invokeBeforeMiddleware();
            $this->response = $this->callRequest();
            $this->invokeAfterMiddleware();
            return $this->readRequest($responseReader);
        } catch (BadResponseException $exception) {
            $this->response = $exception->getResponse();
            $this->invokeAfterMiddleware();
            throw $exception;
        }
    }

    protected function createRequest(): RequestInterface
    {
        return $this->requestConstructor->create(
            $this->httpMethod,
            $this->uri,
            $this->parameters,
            $this->addHeaders
        );
    }

    protected function invokeBeforeMiddleware(): void
    {
        $this->beforeMiddlewareInvoker->createChain($this->beforeMiddleware);
        $this->beforeMiddlewareInvoker->invokeChain($this->request);
    }

    /**
     * @return ResponseInterface
     * @throws \AfSergiu\ApiInvoker\Exceptions\ClientException
     * @throws \AfSergiu\ApiInvoker\Exceptions\NetworkException
     * @throws \AfSergiu\ApiInvoker\Exceptions\ServerException
     * @throws \Throwable
     */
    private function callRequest(): ResponseInterface
    {
        return $this->requestInvoker->invoke($this->request);
    }

    protected function invokeAfterMiddleware(): void
    {
        $this->afterMiddlewareInvoker->createChain($this->afterMiddleware);
        $this->afterMiddlewareInvoker->invokeChain($this->response, $this->request);
    }

    protected function getAfterMiddleware(): array
    {
        return [];
    }

    private function readRequest(IResponseReader $responseReader): mixed
    {
        return $responseReader->read($this->response);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    protected function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
