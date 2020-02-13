<?php
namespace LeoGalleguillos\TestTest;

use Exception;
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

    public function testCreateTable()
    {
        $class = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('createTable');
        $method->setAccessible(true);

        try {
            $method->invoke($this->tableTestCase, 'invalid table name');
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Invalid table name.',
                $exception->getMessage()
            );
        }

        $method->invoke($this->tableTestCase, 'table_1');
    }

    public function testDropTable()
    {
        $class = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('dropTable');
        $method->setAccessible(true);

        try {
            $method->invoke($this->tableTestCase, 'invalid table name');
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Invalid table name.',
                $exception->getMessage()
            );
        }

        $method->invoke($this->tableTestCase, 'table_1');
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
