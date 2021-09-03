<?php declare(strict_types=1);

namespace Application\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DateValidationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $day = $request->getAttribute('day');
        $month = $request->getAttribute('month');

        if (!isset($day, $month)) {
            return $handler->handle($request);
        }

        if (!($day >= 1 && $day <= 31)) {
            return new JsonResponse(
                [
                    'message' => 'Day is not a valid day between 1 and 31',
                ],
                400
            );
        }

        if (!($month >= 1 && $month <= 12)) {
            return new JsonResponse(
                [
                    'message' => 'Month is not a valid month between 1 and 12',
                ],
                400
            );
        }

        return $handler->handle($request->withAttribute('number', $month . '/' . $day));
    }
}
