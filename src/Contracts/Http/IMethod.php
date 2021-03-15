<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

interface IMethod
{
    /**
     * Call and read request
     * @param IResponseReader $responseReader
     * @return mixed
     */
    public function call(IResponseReader $responseReader);

    /**
     * Change default request builder
     * @param IRequestBuilder|array|string $parameters
     * @return mixed
     */
    public function setParameters($parameters);
}
