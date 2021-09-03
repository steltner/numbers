<?php declare(strict_types=1);

namespace Application;

use GuzzleHttp\Client as GuzzleClient;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mezzio\Application;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'aliases' => [
            ],
            'invokables' => [
                Handler\PingHandler::class,
                Handler\NotFoundHandler::class,
                Handler\NumberHandler::class,

                Middleware\NumberValidationMiddleware::class,
                Middleware\DateValidationMiddleware::class,
                Middleware\NumberTypeMiddleware::class,

                GuzzleClient::class,
            ],
            'factories' => [
                Handler\IndexHandler::class => Handler\IndexHandlerFactory::class,

                Middleware\NumberFactMiddleware::class => ConfigAbstractFactory::class,
                Middleware\TranslationMiddleware::class => Middleware\TranslationMiddlewareFactory::class,

                Client\NumbersApiClient::class => ConfigAbstractFactory::class,
                Client\TranslationClient::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    private function getAbstractFactoryConfig(): array
    {
        return [
            Middleware\NumberFactMiddleware::class => [
                Client\NumbersApiClient::class,
            ],

            Client\NumbersApiClient::class => [
                GuzzleClient::class,
            ],
            Client\TranslationClient::class => [
                GuzzleClient::class,
            ],
        ];
    }

    public function registerRoutes(Application $app): void
    {
        $app->get('/', Handler\IndexHandler::class);
        $app->get('/ping', Handler\PingHandler::class);

        $numberPipe = [
            Middleware\NumberValidationMiddleware::class,
            Middleware\NumberTypeMiddleware::class,
            Middleware\NumberFactMiddleware::class,
            Middleware\TranslationMiddleware::class,
            Handler\NumberHandler::class,
        ];

        $app->get('/trivia[/{number:\d+}[/]]', $numberPipe);
        $app->get('/year[/{number:\d+}[/]]', $numberPipe);
        $app->get('/math[/{number:\d+}[/]]', $numberPipe);
        $app->get(
            '/date[/{day:\d+}/{month:\d+}[/]]',
            [
                Middleware\DateValidationMiddleware::class,
                Middleware\NumberTypeMiddleware::class,
                Middleware\NumberFactMiddleware::class,
                Middleware\TranslationMiddleware::class,
                Handler\NumberHandler::class,
            ]
        );
    }
}
