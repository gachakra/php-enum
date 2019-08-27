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
     * @param string $duplicatedValueEnumFqcn
     * @param        $value
     */
    public function thrown_if_enum_has_duplicated_values(string $duplicatedValueEnumFqcn, $value) {

        $this->expectException(DuplicateEnumValueException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        $duplicatedValueEnumFqcn::fromValue($value);
    }

    /**
     * @return Generator|string[]
     */
    public function provideDuplicates(): Generator {

        yield [DuplicatedString::class, DuplicatedString::DUPLICATED_VALUE1];
        yield [DuplicatedString::class, DuplicatedString::DUPLICATED_VALUE2];
        yield [DuplicatedString::class, DuplicatedString::NON_DUPLICATED_VALUE];

        yield [DuplicatedInteger::class, DuplicatedInteger::DUPLICATED_VALUE1];
        yield [DuplicatedInteger::class, DuplicatedInteger::DUPLICATED_VALUE2];
        yield [DuplicatedInteger::class, DuplicatedInteger::NON_DUPLICATED_VALUE];

        yield [DuplicatedFloat::class, DuplicatedFloat::DUPLICATED_VALUE1];
        yield [DuplicatedFloat::class, DuplicatedFloat::DUPLICATED_VALUE2];
        yield [DuplicatedFloat::class, DuplicatedFloat::NON_DUPLICATED_VALUE];

        yield [DuplicatedBool::class, DuplicatedBool::DUPLICATED_VALUE1];
        yield [DuplicatedBool::class, DuplicatedBool::DUPLICATED_VALUE2];
        yield [DuplicatedBool::class, DuplicatedBool::NON_DUPLICATED_VALUE];
    }
}

/**
 * @method static static DUPLICATED_VALUE1()
 * @method static static DUPLICATED_VALUE2()
 * @method static static NON_DUPLICATED_VALUE()
 */
class DuplicatedString extends Enum {

    public const DUPLICATED_VALUE1 = 'duplicates';
    public const DUPLICATED_VALUE2 = 'duplicates';
    public const NON_DUPLICATED_VALUE = 'non-duplicates';
}

/**
 * @method static static DUPLICATED_VALUE1()
 * @method static static DUPLICATED_VALUE2()
 * @method static static NON_DUPLICATED_VALUE()
 */
class DuplicatedInteger extends Enum {

    public const DUPLICATED_VALUE1 = 0;
    public const DUPLICATED_VALUE2 = 0;
    public const NON_DUPLICATED_VALUE = 1;
}

/**
 * @method static static DUPLICATED_VALUE1()
 * @method static static DUPLICATED_VALUE2()
 * @method static static NON_DUPLICATED_VALUE()
 */
class DuplicatedFloat extends Enum {

    public const DUPLICATED_VALUE1 = 0.0;
    public const DUPLICATED_VALUE2 = 0.0;
    public const NON_DUPLICATED_VALUE = 1.0;
}

/**
 * @method static static DUPLICATED_VALUE1()
 * @method static static DUPLICATED_VALUE2()
 * @method static static NON_DUPLICATED_VALUE()
 */
class DuplicatedBool extends Enum {

    public const DUPLICATED_VALUE1 = false;
    public const DUPLICATED_VALUE2 = false;
    public const NON_DUPLICATED_VALUE = true;
}