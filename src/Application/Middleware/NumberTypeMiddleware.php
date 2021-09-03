<?php declare(strict_types=1);

namespace Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function explode;
use function parse_url;

class NumberTypeMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestUri = $request->getServerParams()['REQUEST_URI'];
        $path = parse_url($requestUri)['path'];
        $type = explode('/', $path)[1];

        return $handler->handle($request->withAttribute('type', $type));
    }
}
