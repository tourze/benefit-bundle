# benefit-bundle

[English](README.md) | [中文](README.zh-CN.md)

Symfony 福利包 - 用于在 Symfony 应用程序中管理用户福利和奖励的包。

## 功能特性

- 提供 `BenefitResource` 接口用于实现福利相关资源
- 与 Symfony 框架无缝集成
- 完全兼容 Symfony 6.4+

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本

## 安装

使用 Composer 安装此包：

```bash
composer require tourze/benefit-bundle
```

## 配置

### 启用 Bundle

如果你没有使用 Symfony Flex，需要在 `config/bundles.php` 中手动注册 bundle：

```php
return [
    // ...
    BenefitBundle\BenefitBundle::class => ['all' => true],
];
```

### 基础配置

该 bundle 使用标准的 Symfony 服务配置。服务会自动配置为自动装配和自动配置。

## 使用方法

### 实现 BenefitResource 接口

通过实现 `BenefitResource` 接口创建你自己的福利资源：

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

    // 添加你的福利相关属性和方法
}
```

### 服务集成

Bundle 会自动配置 `BenefitBundle` 命名空间下的服务。你可以在应用中注入并使用它们：

```php
<?php

namespace App\Service;

use BenefitBundle\Model\BenefitResource;

class BenefitManager
{
    public function processBenefit(BenefitResource $benefit): void
    {
        // 处理福利逻辑
    }
}
```

## 测试

运行测试套件：

```bash
# 从 monorepo 根目录运行
./vendor/bin/phpunit packages/benefit-bundle/tests
```

## 贡献

此 bundle 是 tourze/php-monorepo 的一部分。贡献指南请参考主仓库。

## 许可证

此 bundle 在 MIT 许可证下发布。详情请参见附带的 LICENSE 文件。