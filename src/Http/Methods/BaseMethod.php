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
    /**
     * @var string
     */
    protected $httpMethod = 'GET';
    /**
     * @var string
     */
    protected $uri = '';
    /**
     * @var array
     */
    protected $addHeaders=[];
    /**
     * @var array<\Closure|IBeforeMiddleware>
     */
    protected $beforeMiddleware = [];
    /**
     * @var array<\Closure|IAfterMiddleware>
     */
    protected $afterMiddleware = [];
    /**
     * @var mixed
     */
    protected $parameters;
    /**
     * @var IRequestConstructor
     */
    protected $requestConstructor;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var BaseRequestInvoker
     */
    private $requestInvoker;
    /**
     * @var IBeforeMiddlewareInvoker
     */
    private $beforeMiddlewareInvoker;
    /**
     * @var IAfterMiddlewareInvoker
     */
    private $afterMiddlewareInvoker;
    /**
     * @var ResponseInterface
     */
    private $response;

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
     * @param IResponseReader $responseReader
     * @return mixed|void
     * @throws \AfSergiu\ApiInvoker\Exceptions\ClientException
     * @throws \AfSergiu\ApiInvoker\Exceptions\NetworkException
     * @throws \AfSergiu\ApiInvoker\Exceptions\ServerException
     * @throws \Throwable
     * @return mixed
     */
    final public function call(IResponseReader $responseReader)
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

    private function invokeBeforeMiddleware(): void
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

    private function invokeAfterMiddleware(): void
    {
        $this->afterMiddlewareInvoker->createChain($this->afterMiddleware);
        $this->afterMiddlewareInvoker->invokeChain($this->response, $this->request);
    }

    protected function getAfterMiddleware(): array
    {
        return [];
    }

    /**
     * @param IResponseReader $responseReader
     * @return mixed
     */
    private function readRequest(IResponseReader $responseReader)
    {
        return $responseReader->read($this->response);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
