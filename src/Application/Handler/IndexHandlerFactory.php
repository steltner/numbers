<?php declare(strict_types=1);

namespace Application\Handler;

use Psr\Container\ContainerInterface;

class IndexHandlerFactory
{
    public function __invoke(ContainerInterface $container): IndexHandler
    {
        $config = $container->get('config');

        return new IndexHandler($config['languages']);
    }
}
