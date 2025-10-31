<?php

namespace BenefitBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class BenefitExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }
}
