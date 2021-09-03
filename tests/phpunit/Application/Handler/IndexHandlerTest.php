<?php declare(strict_types=1);

namespace Application\Handler;

use Easy\Test\HandlerTestCase;
use Laminas\Diactoros\Response\HtmlResponse;

class IndexHandlerTest extends HandlerTestCase
{
    public function testResponse(): void
    {
        $this->handler = new IndexHandler([
            'de' => 'German',
        ]);

        $response = $this->handler->handle($this->request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertIsString($response->getBody()->getContents());
    }
}
