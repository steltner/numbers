<?php declare(strict_types=1);

namespace Application\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function implode;
use function ob_get_clean;
use function ob_start;
use function sprintf;
use function str_replace;

class IndexHandler implements RequestHandlerInterface
{
    public function __construct(private array $languages)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        ob_start();

        require_once  ROOT . 'templates' . DS . 'layout.html';

        $template = ob_get_clean();

        $template = str_replace('#options#', $this->createLanguageOptions(), $template);

        return new HtmlResponse($template);
    }

    private function createLanguageOptions(): string
    {
        $options = [];

        foreach ($this->languages as $value => $language) {
            $selected = $value === 'en' ? ' selected' : '';
            $options[] = sprintf('<option value="%s"%s>%s</option>', $value, $selected, $language);
        }

        return implode("\n", $options);
    }
}
