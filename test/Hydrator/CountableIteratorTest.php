<?php
namespace LeoGalleguillos\TestTest\Hydrator;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\ResultSet\ResultSet;
use LeoGalleguillos\Test\Hydrator as TestHydrator;
use PHPUnit\Framework\TestCase;

class CountableIteratorTest extends TestCase
{
    protected function setup()
    {
        $this->result = new TestHydrator\CountableIterator();
    }

    public function test_hydrate_empty()
    {
        $countableIteratorMock = $this->createMock(
            Result::class
        );
        $this->result->hydrate(
            $countableIteratorMock,
            []
        );
        $this->assertSame(
            0,
            count($countableIteratorMock)
        );
        $this->assertFalse(
            $countableIteratorMock->current()
        );
        $this->assertSame(
            [],
            iterator_to_array($countableIteratorMock)
        );
    }

    public function test_hydrate_letters()
    {
        $countableIteratorMock = $this->createMock(
            ResultSet::class
        );
        $this->result->hydrate(
            $countableIteratorMock,
            ['a', 'b', 'c',]
        );
        $this->assertSame(
            3,
            count($countableIteratorMock)
        );
        $this->assertSame(
            [
                0 => 'a',
                1 => 'b',
                2 => 'c',
            ],
            iterator_to_array($countableIteratorMock)
        );
    }

    public function test_hydrate_arrays()
    {
        $countableIteratorMock = $this->createMock(
            Result::class
        );
        $this->result->hydrate(
            $countableIteratorMock,
            [
                ['user_id' => 1, 'username' => 'Foo'],
                ['user_id' => 2, 'username' => 'Boo'],
            ]
        );
        $this->assertSame(
            2,
            count($countableIteratorMock)
        );
        $this->assertSame(
            [
                ['user_id' => 1, 'username' => 'Foo'],
                ['user_id' => 2, 'username' => 'Boo'],
            ],
            iterator_to_array($countableIteratorMock)
        );
    }
}
