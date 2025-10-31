# benefit-bundle

[English](README.md) | [中文](README.zh-CN.md)

Symfony Benefit Bundle - A bundle for managing user benefits and rewards in Symfony applications.

## Features

- Provides `BenefitResource` interface for implementing benefit-related resources
- Seamless integration with Symfony framework
- Fully compatible with Symfony 6.4+

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher

## Installation

Install the bundle using Composer:

```bash
composer require tourze/benefit-bundle
```

## Configuration

### Enable the Bundle

If you're not using Symfony Flex, enable the bundle by adding it to the list of registered bundles in `config/bundles.php`:

```php
return [
    // ...
    BenefitBundle\BenefitBundle::class => ['all' => true],
];
```

### Basic Configuration

The bundle uses standard Symfony service configuration. Services are automatically configured with autowiring and autoconfiguration enabled.

## Usage

### Implementing BenefitResource

Create your own benefit resource by implementing the `BenefitResource` interface:

```php
<?php

namespace App\Entity;

use BenefitBundle\Model\BenefitResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class UserBenefit implements BenefitResource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    // Add your benefit-specific properties and methods
}
```

### Service Integration

The bundle automatically configures services in the `BenefitBundle` namespace. You can inject and use them in your application:

```php
<?php

namespace App\Service;

use BenefitBundle\Model\BenefitResource;

class BenefitManager
{
    public function processBenefit(BenefitResource $benefit): void
    {
        // Process benefit logic
    }
}
```

## Testing

Run the test suite:

```bash
# Run from the monorepo root directory
./vendor/bin/phpunit packages/benefit-bundle/tests
```

## Contributing

This bundle is part of the tourze/php-monorepo. Please refer to the main repository for contribution guidelines.

## License

This bundle is released under the MIT License. See the bundled LICENSE file for details.