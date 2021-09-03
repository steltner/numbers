<?php declare(strict_types=1);

namespace Application\Middleware;

use Application\Client\TranslationClient;
use Psr\Container\ContainerInterface;

class TranslationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): TranslationMiddleware
    {
        $translationClient = $container->get(TranslationClient::class);
        $config = $container->get('config');

        return new TranslationMiddleware($translationClient, $config['languages']);
    }
}
