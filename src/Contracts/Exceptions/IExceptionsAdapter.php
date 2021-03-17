<?php declare(strict_types=1);

/**
 * Преобразует исключения библиотек в принятые в данном пакете
 */

namespace AfSergiu\ApiInvoker\Contracts\Exceptions;


interface IExceptionsAdapter
{
    /**
     * @param \Throwable $exception
     * @return \Throwable
     */
    public function adapt(\Throwable $exception): \Throwable;
}
