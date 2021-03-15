<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Exceptions\RequestException;
use AfSergiu\ApiInvoker\Http\Exceptions\NetworkException;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use AfSergiu\ApiInvoker\Http\Exceptions\ServerException;
use AfSergiu\ApiInvoker\Http\Exceptions\ClientException;

class GuzzleExceptionsAdapter implements IExceptionsAdapter
{
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
            $newException = new ClientException();
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
}
