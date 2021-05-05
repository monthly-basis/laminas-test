<?php
namespace MonthlyBasis\LaminasTest\View\Helper;

class Foo
{
    public function __invoke(): string
    {
        return 'foo';
    }
}
