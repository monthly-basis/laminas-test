<?php
namespace MonthlyBasis\LaminasTest\Hydrator;

use Countable;
use Exception;
use Iterator;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

/**
 * Hydrate an object which implements both \Countable and \Iterator
 * with data from an array.
 */
class CountableIterator
{
    public function hydrate(
        Stub $countableIteratorMock,
        array $array
    ) {
        if ((!is_subclass_of($countableIteratorMock, Countable::class))
            || (!is_subclass_of($countableIteratorMock, Iterator::class))) {
            throw new Exception('Mock object does not implement required interfaces.');
        }

        // count
        $countableIteratorMock
            ->method('count')
            ->willReturn(
                count($array)
            );

        // current
        if (empty($array)) {
            $countableIteratorMock
                ->method('current')
                ->willReturn(
                    false
                );
        } else {
            $countableIteratorMock
                ->method('current')
                ->will(
                    call_user_func_array(
                        [TestCase::class, 'onConsecutiveCalls'],
                        $array
                    )
                );
        }

        // key
        $keys = range(0, count($array) - 1);
        $countableIteratorMock
            ->method('key')
            ->will(
                call_user_func_array(
                    [TestCase::class, 'onConsecutiveCalls'],
                    $keys
                )
            );

        // valid
        $valids = array_fill(0, count($array), true);
        $valids[] = false;
        $countableIteratorMock
            ->method('valid')
            ->will(
                call_user_func_array(
                    [TestCase::class, 'onConsecutiveCalls'],
                    $valids
                )
            );
    }
}
