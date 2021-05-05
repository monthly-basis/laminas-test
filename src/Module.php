<?php
namespace MonthlyBasis\LaminasTest;

use MonthlyBasis\LaminasTest\Model\Service as LaminasTestService;

class Module
{
    public function getConfig()
    {
        return [
            'view_helpers' => [
                'aliases' => [
                ],
                'factories' => [
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
