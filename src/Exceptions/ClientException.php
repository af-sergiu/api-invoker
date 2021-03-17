<?php declare(strict_types=1);

/**
 * Ошибки, имеющие http код 4**, кроме 401, 403, 407
 */

namespace AfSergiu\ApiInvoker\Exceptions;

class ClientException extends BadResponseException
{

}
