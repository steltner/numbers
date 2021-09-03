<?php declare(strict_types=1);

namespace Behat;

use Imbo\BehatApiExtension\Context\ApiContext as ImboApiContext;

class ApiContext extends ImboApiContext
{
    protected function getResponseBodyArray()
    {
        return (array)$this->getResponseBody();
    }
}
