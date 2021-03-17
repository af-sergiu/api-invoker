<?php declare(strict_types=1);

/**
 * Ошибка связи с сервером, ответ не получен
 */

namespace AfSergiu\ApiInvoker\Exceptions;

use AfSergiu\ApiInvoker\Contracts\Exceptions\RequestException;

class NetworkException extends \Exception implements RequestException
{

}
