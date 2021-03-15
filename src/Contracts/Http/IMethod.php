<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

interface IMethod
{
    /**
     * Change default request builder
     * @param IRequestBuilder|array|string $parameters
     * @return IMethod
     */
    public function setParameters($parameters): IMethod;

    /**
     * Call and read request
     * @param IResponseReader $responseReader
     * @return mixed
     */
    public function call(IResponseReader $responseReader);
}
