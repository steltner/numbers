<?php declare(strict_types=1);

namespace Application\Handler;

use Easy\Test\HandlerTestCase;
use Laminas\Diactoros\Response\JsonResponse;

use function json_decode;

class NumberHandlerTest extends HandlerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new NumberHandler();
    }

    public function testResponse(): void
    {
        $this->request->expects($this->exactly(3))
            ->method('getAttribute')
            ->withConsecutive(
                ['fact'],
                ['found'],
                ['translation'],
            )
            ->willReturnOnConsecutiveCalls(
                '1 ist a wonderful number',
                true,
                '1 ist eine wundervolle Nummer',
            );

        $response = $this->handler->handle($this->request);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertEquals(
            [
                'original' => '1 ist a wonderful number',
                'message' => '1 ist eine wundervolle Nummer',
                'found' => true,
            ],
            $json
        );
    }

    public function testResponseWithoutTranslation(): void
    {
        $this->request->expects($this->exactly(3))
            ->method('getAttribute')
            ->withConsecutive(
                ['fact'],
                ['found'],
                ['translation'],
            )
            ->willReturnOnConsecutiveCalls(
                '1 ist a wonderful number',
                false,
                null,
            );

        $response = $this->handler->handle($this->request);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertEquals(
            [
                'original' => '1 ist a wonderful number',
                'message' => '1 ist a wonderful number',
                'found' => false,
            ],
            $json
        );
    }
}
