<?php
namespace MonthlyBasis\LaminasTestTest;

use MonthlyBasis\LaminasTest\Model\Command as LaminasTestCommand;
use MonthlyBasis\LaminasTest\Model\Service as LaminasTestService;
use MonthlyBasis\LaminasTest\ModuleTestCase;
use MonthlyBasis\LaminasTest\View\Helper as LaminasTestViewHelper;
use PHPUnit\Framework\TestCase;

class ModuleTestCaseTest extends TestCase
{
    protected function setUp(): void
    {
        $this->moduleTestCase = new ModuleTestCase();
    }

    public function test_testGetConfig_configMethodDoesNotExist_testIsSkipped()
    {
        $this->moduleTestCase->module = (
            new class {}
        );

        $this->moduleTestCase->testGetConfig();
    }

    public function test_testGetConfig_configReturnsArray_testRuns()
    {
        $this->moduleTestCase->module = (new class
        {
            public function getConfig()
            {
                return [
                    'view_helpers' => [
                        'factories' => [
                            LaminasTestViewHelper\Foo::class => function ($sm) {
                                return new LaminasTestViewHelper\Foo();
                            },
                        ],
                    ],
                ];
			}
        });

        $this->moduleTestCase->testGetConfig();
    }

    public function test_testGetConfig_configReturnsArrayContainsLaminasCli_testRuns()
    {
        $this->moduleTestCase->module = (new class
        {
            public function getConfig()
            {
                return [
                    'laminas-cli' => [
                        'commands' => [
                            'foo' => LaminasTestCommand\Foo::class,
                        ],
                    ],
                    'service_manager' => [
                        'factories' => [
                            LaminasTestCommand\Foo::class => function ($sm) {
                                return new LaminasTestCommand\Foo();
                            },
                        ],
                    ],
                ];
			}
        });

        $this->moduleTestCase->testGetConfig();
    }

    public function test_testGetServiceConfig_getServiceConfigMethodDoesNotExist_testIsSkipped()
    {
        $this->moduleTestCase->module = (
            new class {}
        );

        $this->moduleTestCase->testGetServiceConfig();
    }

    public function test_testGetServiceConfig_getServiceConfigMethodReturnsArray_testRuns()
    {
        $this->moduleTestCase->module = (new class
        {
            public function getServiceConfig()
            {
                return [
                    'factories' => [
                        LaminasTestService\Foo::class => function ($sm) {
                            return new LaminasTestService\Foo();
                        },
                    ],
                ];
            }
        });

        $this->moduleTestCase->testGetServiceConfig();
    }
}
