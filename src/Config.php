<?php

declare(strict_types=1);

namespace NasaExtractor;

use Keboola\Component\Config\BaseConfig;

class Config extends BaseConfig
{
    public function getSearchQuery(): string
    {
        return (string)$this->getValue(['parameters', 'searchQuery']);
    }

    public function getPaginationParams(): string
    {
        return (string)http_build_query([
            'page' =>
                [
                    'size' => $this->getValue(['parameters', 'pageSize']),
                    'from' => $this->getValue(['parameters', 'pageFrom'])
                ]
        ]
        );
    }
}
