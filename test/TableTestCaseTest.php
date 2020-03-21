<?php
namespace LeoGalleguillos\TestTest;

use Exception;
use Laminas\Db\Adapter\Adapter;
use LeoGalleguillos\Test\TableTestCase;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class TableTestCaseTest extends TestCase
{
    protected function setup()
    {
        $this->tableTestCase = new TableTestCase();
    }

    public function testCreateTable()
    {
        // Drop table before creating it.
        $class = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('dropTable');
        $method->setAccessible(true);
        $method->invoke($this->tableTestCase, 'table_1');

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

    public function testCreateTables()
    {
        // Drop tables before creating them.
        $class  = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('dropTables');
        $method->setAccessible(true);
        $method->invoke($this->tableTestCase, ['table_1', 'table_2']);

        $class  = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('createTables');
        $method->setAccessible(true);

        $this->assertTrue(
            $method->invoke($this->tableTestCase, ['table_1', 'table_2'])
        );
    }

    public function testDropAndCreateTable()
    {
        $class = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('dropAndCreateTable');
        $method->setAccessible(true);

        $this->assertTrue(
            $method->invoke($this->tableTestCase, 'table_1')
        );
    }

    public function testDropAndCreateTables()
    {
        $class = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('dropAndCreateTables');
        $method->setAccessible(true);

        $this->assertTrue(
            $method->invoke($this->tableTestCase, ['table_1', 'table_2'])
        );
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

    public function testDropTables()
    {
        $class  = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('dropTables');
        $method->setAccessible(true);

        $this->assertTrue(
            $method->invoke($this->tableTestCase, ['table_1', 'table_2'])
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

    public function testSetForeignKeyChecks()
    {
        $class = new ReflectionClass(TableTestCase::class);
        $method = $class->getMethod('setForeignKeyChecks');
        $method->setAccessible(true);

        $this->assertTrue(
            $method->invoke($this->tableTestCase, 0)
        );

        $this->assertTrue(
            $method->invoke($this->tableTestCase, 1)
        );

        // Setting foreign key checks to 1 a second time should still return true.
        $this->assertTrue(
            $method->invoke($this->tableTestCase, 1)
        );

        try {
            $method->invoke($this->tableTestCase, 2);
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Invalid foreign key checks value.',
                $exception->getMessage()
            );
        }
    }
}
