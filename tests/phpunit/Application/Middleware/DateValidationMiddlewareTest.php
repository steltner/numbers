<?php declare(strict_types=1);

namespace Application\Middleware;

use Easy\Test\MiddlewareTestCase;
use Laminas\Diactoros\Response\JsonResponse;

class DateValidationMiddlewareTest extends MiddlewareTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new DateValidationMiddleware();
    }

    public function testWithValidDate(): void
    {
        $day = '1';
        $month = '1';

        $this->request->expects($this->exactly(2))
            ->method('getAttribute')
            ->withConsecutive(
                ['day'],
                ['month'],
            )
            ->willReturnOnConsecutiveCalls(
                $day,
                $month,
            );

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with('number', $day . '/' . $month)
            ->willReturnSelf();

        $this->middleware->process($this->request, $this->handler);
    }

    public function testWithoutDate(): void
    {
        $day = null;
        $month = null;

        $this->request->expects($this->exactly(2))
            ->method('getAttribute')
            ->withConsecutive(
                ['day'],
                ['month'],
            )
            ->willReturnOnConsecutiveCalls(
                $day,
                $month,
            );

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    /**
     * @dataProvider wrongDayDataProvider
     */
    public function testWithWrongDay(string $day): void
    {
        $month = '1';

        $this->request->expects($this->exactly(2))
            ->method('getAttribute')
            ->withConsecutive(
                ['day'],
                ['month'],
            )
            ->willReturnOnConsecutiveCalls(
                $day,
                $month,
            );

        $response = $this->middleware->process($this->request, $this->handler);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertEquals(['message' => 'Day is not a valid day between 1 and 31'], $json);
    }

    /**
     * @dataProvider wrongDateDataProvider
     */
    public function testWithWrongDayAndMonthResultsInDayMessage(string $day, string $month): void
    {
        $this->request->expects($this->exactly(2))
            ->method('getAttribute')
            ->withConsecutive(
                ['day'],
                ['month'],
            )
            ->willReturnOnConsecutiveCalls(
                $day,
                $month,
            );

        $response = $this->middleware->process($this->request, $this->handler);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertEquals(['message' => 'Day is not a valid day between 1 and 31'], $json);
    }

    /**
     * @dataProvider wrongMonthDataProvider
     */
    public function testWithWrongMonth(string $month): void
    {
        $day = '1';

        $this->request->expects($this->exactly(2))
            ->method('getAttribute')
            ->withConsecutive(
                ['day'],
                ['month'],
            )
            ->willReturnOnConsecutiveCalls(
                $day,
                $month,
            );

        $response = $this->middleware->process($this->request, $this->handler);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertEquals(['message' => 'Month is not a valid month between 1 and 12'], $json);
    }

    public function wrongDayDataProvider(): array
    {
        return [
            ['-1'],
            ['0'],
            ['32'],
            ['100000'],
        ];
    }

    public function wrongMonthDataProvider(): array
    {
        return [
            ['-1'],
            ['0'],
            ['13'],
            ['100000'],
        ];
    }

    public function wrongDateDataProvider(): array
    {
        $data = [];

        foreach ($this->wrongDayDataProvider() as $key => $entry) {
            $data[$key][] = current($entry);
        }

        foreach ($this->wrongMonthDataProvider() as $key => $entry) {
            $data[$key][] = current($entry);
        }

        return $data;
    }
}
