<?php
namespace LeoGalleguillos\TestTest;

use LeoGalleguillos\Test\TableTestCase;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Zend\Db\Adapter\Adapter;

class TableTestCaseTest extends TestCase
{
    protected function setup()
    {
        $this->tableTestCase = new TableTestCase();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            TableTestCase::class,
            $this->tableTestCase
        );
    }

    public function testGetAdapter()
    {
        $class = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('getAdapter');
        $method->setAccessible(true);

        $adapter = $method->invoke($this->tableTestCase);

        $this->assertInstanceOf(
            Adapter::class,
            $adapter
        );
    }

    public function testGetConfigArray()
    {
        $class = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('getConfigArray');
        $method->setAccessible(true);

        $array = $method->invoke($this->tableTestCase);
        $this->assertArrayHasKey('driver', $array);
        $this->assertArrayHasKey('database', $array);
        $this->assertArrayHasKey('username', $array);
        $this->assertArrayHasKey('password', $array);
    }
}
