<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Tests\Factories\Mock;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerMockFactory
{
    /**
     * @var TestCase
     */
    private $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function create(array $entries): MockObject
    {
        $mock = $this->testCase->getMockBuilder(ContainerInterface::class)
            ->getMock();
        $mock->method('get')
            ->willReturn($this->testCase->returnCallback(function () use ($entries){
                $key = func_get_arg(0);
                return $entries[$key];
            }));
        return $mock;
    }
}
