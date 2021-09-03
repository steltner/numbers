<?php declare(strict_types=1);

namespace Application\Middleware;

use Application\Client\NumbersApiClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NumberFactMiddleware implements MiddlewareInterface
{
    public function __construct(private NumbersApiClient $client)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $type = $request->getAttribute('type');
        $number = $request->getAttribute('number', 'random');

        $fact = $this->client->getFact($type, $number);

        return $handler->handle(
            $request
                ->withAttribute('fact', $fact['text'])
                ->withAttribute('found', $fact['found'])
        );
    }
}
