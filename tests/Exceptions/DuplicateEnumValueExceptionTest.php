<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-08-04
 * Time: 16:59
 */

namespace Tests\Exceptions;


use Gachakra\PhpEnum\Enum;
use Gachakra\PhpEnum\Exceptions\DuplicateEnumValueException;
use Generator;
use PHPUnit\Framework\TestCase;

class DuplicateEnumValueExceptionTest extends TestCase {

    private const EXCEPTION_MESSAGE = "Each Enum constant must have a different value";

    /**
     * @test
     * @dataProvider provideDuplicates
     *
     * @param string $duplicatedEnumValue
     */
    public function thrown_if_enum_has_duplicated_values(string $duplicatedEnumValue) {

        $this->expectException(DuplicateEnumValueException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        DuplicatedEnum::fromValue($duplicatedEnumValue);
    }


    /**
     * @test
     * @dataProvider provideNonDuplicates
     *
     * @param string $nonDuplicatedEnumValue
     */
    public function thrown_if_even_call_non_duplicates_in_enum_having_duplicated_values(string $nonDuplicatedEnumValue) {

        $this->expectException(DuplicateEnumValueException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        DuplicatedEnum::fromValue($nonDuplicatedEnumValue);
    }

    /**
     * @return Generator|string[]
     */
    public function provideDuplicates(): Generator {

        yield [DuplicatedEnum::DUPLICATED_VALUE1];
        yield [DuplicatedEnum::DUPLICATED_VALUE2];
    }

    /**
     * @return Generator|string[]
     */
    public function provideNonDuplicates(): Generator {

        yield [DuplicatedEnum::NON_DUPLICATED_VALUE1];
        yield [DuplicatedEnum::NON_DUPLICATED_VALUE2];
    }
}

/**
 * Class DuplicatedEnum
 * @package Unit\Exceptions
 *
 * @method static static DUPLICATED_VALUE1()
 * @method static static DUPLICATED_VALUE2()
 * @method static static NON_DUPLICATED_VALUE1()
 * @method static static NON_DUPLICATED_VALUE2()
 */
class DuplicatedEnum extends Enum {

    public const DUPLICATED_VALUE1 = 'value';
    public const DUPLICATED_VALUE2 = 'value';

    public const NON_DUPLICATED_VALUE1 = 'value1';
    public const NON_DUPLICATED_VALUE2 = 'value2';
}