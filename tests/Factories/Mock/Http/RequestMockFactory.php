<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Tests\Factories\Mock\Http;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class RequestMockFactory
{
    /**
     * @var TestCase
     */
    private $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function create(): MockObject
    {
        return $this->testCase->getMockBuilder(RequestInterface::class)
            ->getMock();
    }
}
