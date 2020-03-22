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
        $resultMock
            ->method('current')
            ->will(
                call_user_func_array(
                    [$this, 'onConsecutiveCalls'],
                    $array
                )
            );

        $keys = range(0, count($array) - 1);
        $resultMock
            ->method('key')
            ->will(
                call_user_func_array(
                    [$this, 'onConsecutiveCalls'],
                    $keys
                )
            );

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
