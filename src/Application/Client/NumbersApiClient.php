<?php declare(strict_types=1);

namespace Application\Client;

use InvalidArgumentException;
use GuzzleHttp\Client;
use RuntimeException;

use function is_array;
use function json_decode;
use function sprintf;

class NumbersApiClient
{
    private const URL = 'http://numbersapi.com/%s/%s';

    public function __construct(private Client $client)
    {
    }

    public function getFact(string $type, string $number): array
    {
        $response = $this->client->get(
            sprintf(self::URL, $number, $type),
            [
                'headers' => ['Content-Type' => 'application/json'],
            ],
        );

        if ($response->getStatusCode() !== 200) {
            throw new InvalidArgumentException('Numbers API is gone');
        }

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);

        if (!is_array($body) || !isset($body['text']) || !isset($body['found'])) {
            throw new RuntimeException('Invalid numbers API result');
        }

        return $body;
    }
}
