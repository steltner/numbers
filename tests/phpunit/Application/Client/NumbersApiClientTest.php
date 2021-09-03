<?php declare(strict_types=1);

namespace Application\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function json_encode;

class NumbersApiClientTest extends TestCase
{
    private Client|MockObject $client;
    private NumbersApiClient $numbersApiClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);

        $this->numbersApiClient = new NumbersApiClient($this->client);
    }

    public function testGetFactBadRequest(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Numbers API is gone');

        $this->numbersApiClient = new NumbersApiClient($this->client);

        $type = 'trivia';
        $number = '1';

        $response = new Response(400);

        $this->client->expects($this->once())
            ->method('__call')
            ->with(
                'get',
                [
                    'http://numbersapi.com/1/trivia',
                    [
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
            )
            ->willReturn($response);

        $this->numbersApiClient->getFact($type, $number);
    }

    public function testGetFactInvalidResultMissingText(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid numbers API result');

        $type = 'trivia';
        $number = '1';

        $response = new Response(200, [], '');

        $this->client->expects($this->once())
            ->method('__call')
            ->with(
                'get',
                [
                    'http://numbersapi.com/1/trivia',
                    [
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
            )
            ->willReturn($response);

        $this->numbersApiClient->getFact($type, $number);
    }

    public function testGetFactInvalidResultMissingFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid numbers API result');

        $type = 'trivia';
        $number = '1';
        $result = ['text' => '1 ist a wonderful number'];

        $response = new Response(200, [], json_encode($result));

        $this->client->expects($this->once())
            ->method('__call')
            ->with(
                'get',
                [
                    'http://numbersapi.com/1/trivia',
                    [
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
            )
            ->willReturn($response);

        $this->numbersApiClient->getFact($type, $number);
    }

    public function testGetFactSuccess(): void
    {
        $type = 'trivia';
        $number = '1';
        $result = ['text' => '1 ist a wonderful number', 'found' => true];

        $response = new Response(200, [], json_encode($result));

        $this->client->expects($this->once())
            ->method('__call')
            ->with(
                'get',
                [
                    'http://numbersapi.com/1/trivia',
                    [
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
            )
            ->willReturn($response);

        $this->assertSame($result, $this->numbersApiClient->getFact($type, $number));
    }
}
