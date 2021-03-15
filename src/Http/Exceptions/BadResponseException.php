<?php declare(strict_types=1);

/**
 * Ошибка запроса, содержащая ответ от сервера
 */

namespace AfSergiu\ApiInvoker\Http\Exceptions;

use AfSergiu\ApiInvoker\Contracts\Exceptions\RequestException;
use Psr\Http\Message\ResponseInterface;

abstract class BadResponseException extends \Exception implements RequestException
{
    /**
     * @var ResponseInterface $response
     */
    private $response;

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    /**
     * Возвращает
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * Проверяет установлен ли ответ
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->response != null;
    }
}
