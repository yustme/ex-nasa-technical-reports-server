<?php

declare(strict_types=1);

namespace NasaExtractor;

use Keboola\Component\Config\BaseConfig;

class Config extends BaseConfig
{
    // @todo implement your custom getters
    public function getSearchQuery(): string
    {
        return $this->getValue(['parameters', 'searchQuery']);
    }
}
