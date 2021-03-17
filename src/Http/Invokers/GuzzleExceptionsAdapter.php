<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Exceptions\RequestException;
use AfSergiu\ApiInvoker\Exceptions\NetworkException;
use AfSergiu\ApiInvoker\Exceptions\ServerAccessException;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use AfSergiu\ApiInvoker\Exceptions\ServerException;
use AfSergiu\ApiInvoker\Exceptions\ClientException;

final class GuzzleExceptionsAdapter implements IExceptionsAdapter
{
    /**
     * @var array
     */
    const SERVER_ACCESS_ERROR_CODES = [401, 403, 407];

    /**
     * @param \Throwable $exception
     * @return \Throwable
     */
    public function adapt(\Throwable $exception): \Throwable
    {
        if ($exception instanceof GuzzleServerException) {
            $newException = new ServerException();
            $newException->setResponse($exception->getResponse());
        } else if ($exception instanceof GuzzleClientException) {
            $newException = $this->resolveClientException($exception);
            $newException->setResponse($exception->getResponse());
        } else if ($exception instanceof GuzzleConnectException) {
            $newException = new NetworkException();
        } else if ($exception instanceof TooManyRedirectsException) {
            $newException = new ServerException();
        } else if ($exception instanceof RequestException) {
            $newException = new NetworkException();
        } else {
            $newException = $exception;
        }
        return $newException;
    }

    private function resolveClientException(GuzzleClientException $exception): ClientException
    {
        $statusCode = $exception->getCode();
        if (in_array($statusCode, self::SERVER_ACCESS_ERROR_CODES)) {
            return new ServerAccessException();
        } else {
            return new ClientException();
        }
    }
}
