<?php

declare(strict_types=1);

namespace BenefitBundle\Tests\Service;

use BenefitBundle\Entity\Benefit;
use BenefitBundle\Service\AdminMenu;
use Knp\Menu\MenuFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * AdminMenu服务测试
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    protected function onSetUp(): void
    {
        // Setup for AdminMenu tests
    }

    public function testInvokeAddsMenuItems(): void
    {
        $container = self::getContainer();
        /** @var AdminMenu $adminMenu */
        $adminMenu = $container->get(AdminMenu::class);

        $factory = new MenuFactory();
        $rootItem = $factory->createItem('root');

        $adminMenu->__invoke($rootItem);

        // 验证菜单结构
        $benefitMenu = $rootItem->getChild('福利管理');
        self::assertNotNull($benefitMenu);

        $benefitListMenu = $benefitMenu->getChild('福利列表');
        self::assertNotNull($benefitListMenu);

        // 验证菜单图标设置
        self::assertEquals('fas fa-gift', $benefitListMenu->getAttribute('icon'));

        // 验证菜单URI
        self::assertNotEmpty($benefitListMenu->getUri());
    }

    public function testInvokeWithExistingBenefitMenuGroup(): void
    {
        $container = self::getContainer();
        /** @var AdminMenu $adminMenu */
        $adminMenu = $container->get(AdminMenu::class);

        $factory = new MenuFactory();
        $rootItem = $factory->createItem('root');

        // 预先创建福利管理菜单组
        $existingBenefitMenu = $rootItem->addChild('福利管理');

        $adminMenu->__invoke($rootItem);

        // 验证菜单结构 - 应该使用现有的菜单组
        $benefitMenu = $rootItem->getChild('福利管理');
        self::assertSame($existingBenefitMenu, $benefitMenu);

        $benefitListMenu = $benefitMenu->getChild('福利列表');
        self::assertNotNull($benefitListMenu);

        // 验证菜单图标设置
        self::assertEquals('fas fa-gift', $benefitListMenu->getAttribute('icon'));

        // 验证菜单URI
        self::assertNotEmpty($benefitListMenu->getUri());
    }

    public function testLinkGeneratorIntegration(): void
    {
        $container = self::getContainer();
        /** @var LinkGeneratorInterface $linkGenerator */
        $linkGenerator = $container->get(LinkGeneratorInterface::class);

        // 测试链接生成器可以正确生成Benefit实体的链接
        $link = $linkGenerator->getCurdListPage(Benefit::class);
        self::assertIsString($link);
        self::assertNotEmpty($link);
    }
}
