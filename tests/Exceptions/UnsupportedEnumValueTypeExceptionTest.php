<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-08-04
 * Time: 16:59
 */

namespace Tests\Exceptions;


use Gachakra\PhpEnum\Enum;
use Gachakra\PhpEnum\Exceptions\UnsupportedEnumValueTypeException;
use Generator;
use PHPUnit\Framework\TestCase;

class UnsupportedEnumValueTypeExceptionTest extends TestCase {

    private const EXCEPTION_MESSAGE_FORMAT = "Enum value must be scalar or null, but %s given";

    /**
     * @test
     * @dataProvider provideEnumPublicMethods
     * @param callable $publicMethod
     */
    public function thrown_if_enum_has_array_value(callable $publicMethod) {

        $this->expectException(UnsupportedEnumValueTypeException::class);
        $this->expectExceptionMessage(sprintf(self::EXCEPTION_MESSAGE_FORMAT, 'array'));

        $publicMethod();
    }


    public function provideEnumPublicMethods(): Generator {

        yield [function () {
            ArrayTypeEnum::PUBLIC_ARRAY();
        }];
        yield [function () {
            ArrayTypeEnum::PROTECTED_ARRAY();
        }];
        yield [function () {
            ArrayTypeEnum::PRIVATE_ARRAY();
        }];
        yield [function () {
            ArrayTypeEnum::NON_ARRAY();
        }];
        yield [function () {
            ArrayTypeEnum::NON_EXISTING_ELEMENT();
        }];
        yield [function () {
            ArrayTypeEnum::has('PUBLIC_ARRAY');
        }];
        yield [function () {
            ArrayTypeEnum::has('PROTECTED_ARRAY');
        }];
        yield [function () {
            ArrayTypeEnum::has('PRIVATE_ARRAY');
        }];
        yield [function () {
            ArrayTypeEnum::has('NON_ARRAY');
        }];
        yield [function () {
            ArrayTypeEnum::has('NON_EXISTING_NAME');
        }];
        yield [function () {
            ArrayTypeEnum::of('PUBLIC_ARRAY');
        }];
        yield [function () {
            ArrayTypeEnum::of('PROTECTED_ARRAY');
        }];
        yield [function () {
            ArrayTypeEnum::of('PRIVATE_ARRAY');
        }];
        yield [function () {
            ArrayTypeEnum::of('NON_ARRAY');
        }];
        yield [function () {
            ArrayTypeEnum::of('NON_EXISTING_NAME');
        }];
        yield [function () {
            ArrayTypeEnum::fromValue([]);
        }];
        yield [function () {
            ArrayTypeEnum::fromValue(['array']);
        }];
        yield [function () {
            ArrayTypeEnum::fromValue('non_array');
        }];
        yield [function () {
            ArrayTypeEnum::fromValue('non_existing_value');
        }];
        yield [function () {
            ArrayTypeEnum::__callStatic('PUBLIC_ARRAY', []);
        }];
        yield [function () {
            ArrayTypeEnum::__callStatic('PROTECTED_ARRAY', []);
        }];
        yield [function () {
            ArrayTypeEnum::__callStatic('PRIVATE_ARRAY', []);
        }];
        yield [function () {
            ArrayTypeEnum::__callStatic('NON_ARRAY', []);
        }];
        yield [function () {
            ArrayTypeEnum::__callStatic('NON_EXISTING_NAME', []);
        }];
        yield [function () {
            ArrayTypeEnum::elements();
        }];
        yield [function () {
            ArrayTypeEnum::constants();
        }];
        yield [function () {
            ArrayTypeEnum::names();
        }];
        yield [function () {
            ArrayTypeEnum::values();
        }];
        yield [function () {
            ArrayTypeEnum::toStrings();
        }];
    }
}

/**
 * @method static self PUBLIC_ARRAY()
 * @method static self PROTECTED_ARRAY()
 * @method static self PRIVATE_ARRAY()
 * @method static self NON_ARRAY()
 */
class ArrayTypeEnum extends Enum {

    public const PUBLIC_ARRAY = [];
    protected const PROTECTED_ARRAY = [];
    private const PRIVATE_ARRAY = ['array'];

    const NON_ARRAY = 'non_array';
}