<?php

declare(strict_types=1);

namespace BenefitBundle\Tests;

use BenefitBundle\BenefitBundle;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(BenefitBundle::class)]
#[RunTestsInSeparateProcesses]
final class BenefitBundleTest extends AbstractBundleTestCase
{
}
