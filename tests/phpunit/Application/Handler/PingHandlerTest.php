<?php declare(strict_types=1);

namespace Application\Handler;

use Easy\Test\HandlerTestCase;
use Laminas\Diactoros\Response\JsonResponse;

class PingHandlerTest extends HandlerTestCase
{
    public function testResponse(): void
    {
        $this->handler = new PingHandler();

        $response = $this->handler->handle($this->request);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue(isset($json['time']));
    }
}
