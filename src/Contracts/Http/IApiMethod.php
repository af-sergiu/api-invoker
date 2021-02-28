<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

interface IApiMethod
{
    /**
     * Call and read request
     * @param IResponseReader $responseReader
     * @return mixed
     */
    public function call(IResponseReader $responseReader);

    /**
     * Change default request builder
     * @param IRequestBuilder $requestBuilder
     * @return mixed
     */
    public function setRequestBuilder(IRequestBuilder $requestBuilder);

    /**
     * Set request parameters
     * @param array $parameters
     * @return mixed
     */
    public function setRequestParameters(array $parameters);
}
