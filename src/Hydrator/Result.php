<?php
namespace LeoGalleguillos\Test\Hydrator;

use PHPUnit\Framework\TestCase;

/**
 * Hydrate a Result mock with data from an array.
 */
class Result extends TestCase
{
    public function hydrate(
        $resultMock,
        array $array
    ) {
        // count
        $resultMock
            ->method('count')
            ->willReturn(
                count($array)
            );

        // current
        if (empty($array)) {
            $resultMock
                ->method('current')
                ->willReturn(
                    false
                );
        } else {
            $resultMock
                ->method('current')
                ->will(
                    call_user_func_array(
                        [$this, 'onConsecutiveCalls'],
                        $array
                    )
                );
        }

        // key
        $keys = range(0, count($array) - 1);
        $resultMock
            ->method('key')
            ->will(
                call_user_func_array(
                    [$this, 'onConsecutiveCalls'],
                    $keys
                )
            );

        // valid
        $valids = array_fill(0, count($array), true);
        $valids[] = false;
        $resultMock
            ->method('valid')
            ->will(
                call_user_func_array(
                    [$this, 'onConsecutiveCalls'],
                    $valids
                )
            );
    }
}
