<?php declare(strict_types=1);

namespace Application\Middleware;

use Application\Client\NumbersApiClient;
use Easy\Test\MiddlewareTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class NumberFactMiddlewareTest extends MiddlewareTestCase
{
    private NumbersApiClient|MockObject $numbersApiClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->numbersApiClient = $this->createMock(NumbersApiClient::class);

        $this->middleware = new NumberFactMiddleware($this->numbersApiClient);
    }

    /**
     * @dataProvider getFactDataProvider
     */
    public function testGetFact(string $type, string $number, string $fact, bool $found): void
    {
        $this->request->expects($this->exactly(2))
            ->method('getAttribute')
            ->withConsecutive(
                ['type'],
                ['number', 'random'],
            )
            ->willReturnOnConsecutiveCalls(
                $type,
                $number,
            );

        $this->numbersApiClient->expects($this->once())
            ->method('getFact')
            ->with($type, $number)
            ->willReturn(['text' => $fact, 'found' => $found]);

        $this->request->expects($this->exactly(2))
            ->method('withAttribute')
            ->withConsecutive(
                ['fact', $fact],
                ['found', $found],
            )
            ->willReturnSelf();

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function getFactDataProvider(): array
    {
        return [
            ['trivia', '1', '1 ist a wonderful number', true],
            ['date', '1/1', '1 ist a wonderful number', false],
        ];
    }
}
