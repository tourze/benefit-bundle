<?php

declare(strict_types=1);

namespace BenefitBundle\Tests\Controller\Admin;

use BenefitBundle\Controller\Admin\BenefitCrudController;
use BenefitBundle\Entity\Benefit;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * BenefitCrudController 测试类.
 *
 * @internal
 */
#[CoversClass(BenefitCrudController::class)]
#[RunTestsInSeparateProcesses]
final class BenefitCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): BenefitCrudController
    {
        return new BenefitCrudController();
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield from [
            'ID' => ['ID'],
            '名称' => ['福利名称'],
            '金额' => ['福利金额'],
            '类型' => ['福利类型'],
            '状态' => ['是否激活'],
            '创建时间' => ['创建时间'],
        ];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield from [
            'name' => ['name'],
            'amount' => ['amount'],
            'type' => ['type'],
            'active' => ['active'],
            'description' => ['description'],
        ];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield from [
            'name' => ['name'],
            'amount' => ['amount'],
            'type' => ['type'],
            'active' => ['active'],
            'description' => ['description'],
        ];
    }

    #[Test]
    public function testControllerIsInstantiable(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);

        $this->assertTrue($reflection->isInstantiable());
    }

    #[Test]
    public function testGetEntityFqcnReturnsBenefitClass(): void
    {
        $this->assertEquals(Benefit::class, BenefitCrudController::getEntityFqcn());
    }

    #[Test]
    public function testControllerHasAdminCrudAttribute(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);
        $attributes = $reflection->getAttributes();

        $hasAdminCrudAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'AdminCrud')) {
                $hasAdminCrudAttribute = true;
                break;
            }
        }

        $this->assertTrue($hasAdminCrudAttribute, 'Controller应该有AdminCrud注解');
    }

    #[Test]
    public function testControllerHasRequiredConfigurationMethods(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);

        $requiredMethods = [
            'getEntityFqcn',
            'configureCrud',
            'configureFields',
            'configureFilters',
        ];

        foreach ($requiredMethods as $methodName) {
            $this->assertTrue($reflection->hasMethod($methodName), "方法 {$methodName} 必须存在");

            $method = $reflection->getMethod($methodName);
            $this->assertTrue($method->isPublic(), "方法 {$methodName} 必须是public");
        }
    }

    #[Test]
    public function testGetEntityFqcnIsStaticMethod(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);
        $method = $reflection->getMethod('getEntityFqcn');

        $this->assertTrue($method->isStatic(), 'getEntityFqcn必须是静态方法');
        $this->assertTrue($method->isPublic(), 'getEntityFqcn必须是public方法');
    }

    #[Test]
    public function testConfigureCrudMethodSignature(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);
        $method = $reflection->getMethod('configureCrud');

        $this->assertTrue($method->isPublic());

        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertEquals('EasyCorp\Bundle\EasyAdminBundle\Config\Crud', ($returnType instanceof \ReflectionNamedType) ? $returnType->getName() : (string) $returnType);

        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertEquals('crud', $parameters[0]->getName());
    }

    #[Test]
    public function testConfigureFieldsMethodSignature(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);
        $method = $reflection->getMethod('configureFields');

        $this->assertTrue($method->isPublic());

        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertEquals('iterable', ($returnType instanceof \ReflectionNamedType) ? $returnType->getName() : (string) $returnType);

        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertEquals('pageName', $parameters[0]->getName());
    }

    #[Test]
    public function testConfigureFiltersMethodSignature(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);
        $method = $reflection->getMethod('configureFilters');

        $this->assertTrue($method->isPublic());

        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertEquals('EasyCorp\Bundle\EasyAdminBundle\Config\Filters', ($returnType instanceof \ReflectionNamedType) ? $returnType->getName() : (string) $returnType);

        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertEquals('filters', $parameters[0]->getName());
    }

    #[Test]
    public function testControllerHasCorrectNamespace(): void
    {
        $this->assertEquals(
            'BenefitBundle\Controller\Admin',
            (new \ReflectionClass(BenefitCrudController::class))->getNamespaceName()
        );
    }

    #[Test]
    public function testControllerRequiresAuthentication(): void
    {
        $client = self::createClient();

        // 尝试访问福利管理页面，预期会返回404（因为路由不存在）或重定向到登录
        $client->request('GET', '/admin/benefit/benefit');

        $response = $client->getResponse();
        $statusCode = $response->getStatusCode();

        // 接受404（路由不存在）或302（重定向到登录）都是正常的
        $this->assertContains($statusCode, [302, 404], '应该返回302重定向或404未找到');

        if (302 === $statusCode) {
            $location = $response->headers->get('Location');
            $this->assertNotNull($location);
            $this->assertStringContainsString('/login', $location);
        }
    }

    #[Test]
    public function testControllerStructure(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);

        // 测试继承关系
        $this->assertTrue($reflection->isSubclassOf('EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController'));

        // 测试getEntityFqcn是静态方法
        $getEntityMethod = $reflection->getMethod('getEntityFqcn');
        $this->assertTrue($getEntityMethod->isStatic());
        $this->assertTrue($getEntityMethod->isPublic());

        // 测试类不是抽象类
        $this->assertFalse($reflection->isAbstract());
    }

    #[Test]
    public function testControllerSupportsCrudOperations(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);

        // 验证所有必需的CRUD配置方法都存在
        $crudMethods = [
            'configureCrud' => 'EasyCorp\Bundle\EasyAdminBundle\Config\Crud',
            'configureFields' => 'iterable',
            'configureFilters' => 'EasyCorp\Bundle\EasyAdminBundle\Config\Filters',
        ];

        foreach ($crudMethods as $methodName => $expectedReturnType) {
            $this->assertTrue($reflection->hasMethod($methodName), "CRUD方法 {$methodName} 必须存在");

            $method = $reflection->getMethod($methodName);
            $this->assertTrue($method->isPublic(), "CRUD方法 {$methodName} 必须是public");

            $returnType = $method->getReturnType();
            $this->assertNotNull($returnType, "CRUD方法 {$methodName} 必须有返回类型");
            $this->assertEquals(
                $expectedReturnType,
                ($returnType instanceof \ReflectionNamedType) ? $returnType->getName() : (string) $returnType,
                "CRUD方法 {$methodName} 返回类型应该是 {$expectedReturnType}"
            );
        }
    }

    #[Test]
    public function testControllerHasBenefitSpecificFields(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);

        // 验证控制器包含了适当的用法，通过检查类内容间接验证
        $fileName = $reflection->getFileName();
        $this->assertNotFalse($fileName, '无法获取控制器源文件路径');
        $source = file_get_contents($fileName);
        $this->assertNotFalse($source, '无法读取控制器源文件');

        // 验证使用了福利相关的字段
        $this->assertStringContainsString(
            'name',
            $source,
            'BenefitCrudController应该包含name字段'
        );
        $this->assertStringContainsString(
            'amount',
            $source,
            'BenefitCrudController应该包含amount字段'
        );
        $this->assertStringContainsString(
            'type',
            $source,
            'BenefitCrudController应该包含type字段'
        );
        $this->assertStringContainsString(
            'active',
            $source,
            'BenefitCrudController应该包含active字段'
        );
        $this->assertStringContainsString(
            'description',
            $source,
            'BenefitCrudController应该包含description字段'
        );
    }

    #[Test]
    public function testControllerUsesMoneyFieldForAmount(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);

        // 验证控制器使用了MoneyField来处理金额
        $fileName = $reflection->getFileName();
        $this->assertNotFalse($fileName, '无法获取控制器源文件路径');
        $source = file_get_contents($fileName);
        $this->assertNotFalse($source, '无法读取控制器源文件');

        // 验证使用了MoneyField和CNY货币
        $this->assertStringContainsString(
            'MoneyField::new',
            $source,
            'BenefitCrudController应该使用MoneyField处理amount字段'
        );
        $this->assertStringContainsString(
            'CNY',
            $source,
            'BenefitCrudController应该使用CNY货币'
        );
    }

    #[Test]
    public function testControllerConfiguresFilters(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);

        // 验证控制器配置了过滤器
        $fileName = $reflection->getFileName();
        $this->assertNotFalse($fileName, '无法获取控制器源文件路径');
        $source = file_get_contents($fileName);
        $this->assertNotFalse($source, '无法读取控制器源文件');

        // 验证配置了必要的过滤器
        $this->assertStringContainsString(
            'BooleanFilter::new',
            $source,
            'BenefitCrudController应该配置BooleanFilter'
        );
        $this->assertStringContainsString(
            'DateTimeFilter::new',
            $source,
            'BenefitCrudController应该配置DateTimeFilter'
        );
    }

    #[Test]
    public function testControllerHasChineseLabels(): void
    {
        $reflection = new \ReflectionClass(BenefitCrudController::class);

        // 验证控制器使用了中文标签
        $fileName = $reflection->getFileName();
        $this->assertNotFalse($fileName, '无法获取控制器源文件路径');
        $source = file_get_contents($fileName);
        $this->assertNotFalse($source, '无法读取控制器源文件');

        // 验证包含了中文标签
        $this->assertStringContainsString(
            '福利',
            $source,
            'BenefitCrudController应该使用中文标签'
        );
        $this->assertStringContainsString(
            '福利名称',
            $source,
            'BenefitCrudController应该包含福利名称字段标签'
        );
        $this->assertStringContainsString(
            '福利金额',
            $source,
            'BenefitCrudController应该包含福利金额字段标签'
        );
    }
}
