<?php declare(strict_types=1);

use Easy\InvocableResolver;
use Mezzio\Application as MezzioApplication;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    public function testConfigProvider(): void
    {
        $application = $this->createMock(MezzioApplication::class);

        $providers = (new InvocableResolver())->resolveList(
            [
                new Application\ConfigProvider(),

                Application\ConfigProvider::class,
            ]
        );

        foreach ($providers as $provider) {
            $this->assertTrue(is_callable($provider));

            $config = $provider();

            $this->assertTrue(is_array($config));

            if (is_callable([$provider, 'registerRoutes'])) {
                $provider->registerRoutes($application);
            }
        }
    }

    public function testInvalidProvider(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new InvocableResolver())->resolveList(['abc']);
    }
}
