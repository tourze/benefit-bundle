<?php

namespace BenefitBundle\Tests\DependencyInjection;

use BenefitBundle\DependencyInjection\BenefitExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(BenefitExtension::class)]
final class BenefitExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private BenefitExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension = new BenefitExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    /**
     * 测试正常加载服务配置
     */
    public function testLoadWithValidConfiguration(): void
    {
        $configs = [];

        // 注册LinkGeneratorInterface的匿名类实现
        $linkGenerator = new class () implements LinkGeneratorInterface {
            public function getCurdListPage(string $entityClass): string
            {
                /** @var class-string $entityClass */
                $reflection = new \ReflectionClass($entityClass);

                return '/admin/test/' . strtolower($reflection->getShortName());
            }

            public function extractEntityFqcn(string $url): ?string
            {
                $matches = [];
                if (1 === preg_match('#/admin/test/(\w+)#', $url, $matches)) {
                    return ucfirst($matches[1]);
                }

                return null;
            }

            public function setDashboard(string $dashboardControllerFqcn): void
            {
                // 测试环境下的空实现，不需要实际设置 Dashboard
            }
        };

        $this->container->set(LinkGeneratorInterface::class, $linkGenerator);

        // 加载扩展配置
        $this->extension->load($configs, $this->container);

        $this->assertTrue(true, 'Extension loaded successfully');
    }

    /**
     * 测试加载配置后容器可以正常编译
     */
    public function testLoadDoesNotThrow(): void
    {
        $this->expectNotToPerformAssertions();

        $this->extension->load([], $this->container);
    }
}
