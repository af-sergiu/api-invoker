<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

interface IMethod
{
    /**
     * Change default request builder
     * @param IRequestBuilder|array|string $parameters
     */
    public function setParameters(mixed $parameters): IMethod;

    /**
     * Call and read request
     * @param IResponseReader $responseReader
     * @return mixed
     */
    public function call(IResponseReader $responseReader): mixed;
}
