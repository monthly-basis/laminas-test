<?php
namespace MonthlyBasis\LaminasTestTest;

use MonthlyBasis\LaminasTest\ModuleTestCase;
use MonthlyBasis\LaminasTest\View\Helper as LaminasTestViewHelper;
use PHPUnit\Framework\TestCase;

class ModuleTestCaseTest extends TestCase
{
    protected function setUp(): void
    {
        $this->moduleTestCase = new ModuleTestCase();
    }

    public function test_testGetConfig_configMethodDoesNotExist_TestIsSkipped()
    {
        $this->moduleTestCase->module = (
            new class {}
        );

        $this->moduleTestCase->testGetConfig();
    }

    public function test_testGetConfig_configReturnsArray_TestRuns()
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
}
