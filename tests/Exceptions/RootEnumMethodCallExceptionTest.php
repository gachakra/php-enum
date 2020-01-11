<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-08-04
 * Time: 16:59
 */

namespace Tests\Exceptions;


use Gachakra\PhpEnum\Enum;
use Gachakra\PhpEnum\Exceptions\RootEnumMethodCallException;
use Generator;
use PHPUnit\Framework\TestCase;

class RootEnumMethodCallExceptionTest extends TestCase {

    private const EXCEPTION_MESSAGE = "Cannot call static method directly via root abstract Enum";

    /**
     * @test
     * @dataProvider provideEnumStaticMethodNames
     *
     * @param callable $publicStaticMethod
     */
    public function thrown_if_called_static_method_via_abstract_enum_directly(callable $publicStaticMethod) {

        $this->expectException(RootEnumMethodCallException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        $publicStaticMethod();
    }

    public function provideEnumStaticMethodNames(): Generator {

        yield [function () {
            Enum::elements();
        }];
        yield [function () {
            Enum::constants();
        }];
        yield [function () {
            Enum::names();
        }];
        yield [function () {
            Enum::values();
        }];
        yield [function () {
            Enum::toStrings();
        }];
        yield [function () {
            Enum::has('something');
        }];
        yield [function () {
            Enum::of('anything');
        }];
        yield [function () {
            Enum::fromValue('whatever');
        }];
        yield [function () {
            Enum::__callStatic('whocares', []);
        }];
    }
}