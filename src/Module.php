<?php
namespace MonthlyBasis\LaminasTest;

use MonthlyBasis\LaminasTest\Controller as LaminasTestController;
use MonthlyBasis\LaminasTest\Model\Command as LaminasTestCommand;
use MonthlyBasis\LaminasTest\Model\Service as LaminasTestService;

class Module
{
    public function getConfig()
    {
        return [
            'controllers' => [
                'factories' => [
                    LaminasTestController\Foo::class => function ($sm) {
                        return new LaminasTestController\Foo();
                    }
                ],
            ],
            'view_helpers' => [
                'aliases' => [
                ],
                'factories' => [
                ],
            ],
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
}
