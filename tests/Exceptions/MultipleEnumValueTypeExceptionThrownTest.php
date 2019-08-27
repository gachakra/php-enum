<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-08-04
 * Time: 16:59
 */

namespace Tests\Exceptions;


use Gachakra\PhpEnum\Enum;
use Gachakra\PhpEnum\Exceptions\MultipleEnumValueTypeException;
use Generator;
use PHPUnit\Framework\TestCase;

class MultipleEnumValueTypeExceptionThrownTest extends TestCase {

    private const EXCEPTION_MESSAGE = "All the Enum constants values must be the same type";

    /**
     * @test
     * @dataProvider provideMultipleTypeValues
     *
     * @param string                $enumFqcn
     * @param bool|float|int|string $value
     */
    public function thrown_if_enum_has_multiple_value_types(string $enumFqcn, $value) {

        $this->expectException(MultipleEnumValueTypeException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        $enumFqcn::fromValue($value);
    }

    /**
     * @return Generator|string[]
     */
    public function provideMultipleTypeValues(): Generator {

        {
            yield [StringAndInteger::class, StringAndInteger::STRING];
            yield [StringAndInteger::class, StringAndInteger::INTEGER];

            yield [StringAndFloat::class, StringAndFloat::STRING];
            yield [StringAndFloat::class, StringAndFloat::FLOAT];

            yield [StringAndBool::class, StringAndBool::STRING];
            yield [StringAndBool::class, StringAndBool::BOOL];

            yield [IntegerAndFloat::class, IntegerAndFloat::INTEGER];
            yield [IntegerAndFloat::class, IntegerAndFloat::FLOAT];

            yield [IntegerAndBool::class, IntegerAndBool::INTEGER];
            yield [IntegerAndBool::class, IntegerAndBool::BOOL];

            yield [FloatAndBool::class, FloatAndBool::FLOAT];
            yield [FloatAndBool::class, FloatAndBool::BOOL];
        }

        // backwards
        {
            yield [BoolAndFloat::class, BoolAndFloat::BOOL];
            yield [BoolAndFloat::class, BoolAndFloat::FLOAT];

            yield [BoolAndInteger::class, BoolAndInteger::BOOL];
            yield [BoolAndInteger::class, BoolAndInteger::INTEGER];

            yield [BoolAndString::class, BoolAndString::BOOL];
            yield [BoolAndString::class, BoolAndString::STRING];

            yield [FloatAndInteger::class, FloatAndInteger::FLOAT];
            yield [FloatAndInteger::class, FloatAndInteger::INTEGER];

            yield [FloatAndString::class, FloatAndString::FLOAT];
            yield [FloatAndString::class, FloatAndString::STRING];

            yield [IntegerAndString::class, IntegerAndString::INTEGER];
            yield [IntegerAndString::class, IntegerAndString::STRING];
        }
    }
}

{
    /**
     * @method static static STRING()
     * @method static static INTEGER()
     */
    class StringAndInteger extends Enum {

        public const STRING = '0';
        public const INTEGER = 0;
    }

    /**
     * @method static static STRING()
     * @method static static FLOAT()
     */
    class StringAndFloat extends Enum {

        public const STRING = '0';
        public const FLOAT = 0.0;
    }

    /**
     * @method static static STRING()
     * @method static static BOOL()
     */
    class StringAndBool extends Enum {

        public const STRING = '0';
        public const BOOL = false;
    }

    /**
     * @method static static INTEGER()
     * @method static static FLOAT()
     */
    class IntegerAndFloat extends Enum {

        public const INTEGER = 0;
        public const FLOAT = 0.0;
    }

    /**
     * @method static static INTEGER()
     * @method static static BOOL()
     */
    class IntegerAndBool extends Enum {

        public const INTEGER = 0;
        public const BOOL = false;
    }

    /**
     * @method static static FLOAT()
     * @method static static BOOL()
     */
    class FloatAndBool extends Enum {

        public const FLOAT = 0.0;
        public const BOOL = false;
    }
}

/**
 * Definite constants in reverse order
 */
{
    /**
     * @method static static BOOL()
     * @method static static FLOAT()
     */
    class BoolAndFloat extends Enum {

        public const BOOL = true;
        public const FLOAT = 1.0;
    }

    /**
     * @method static static BOOL()
     * @method static static INTEGER()
     */
    class BoolAndInteger extends Enum {

        public const BOOL = true;
        public const INTEGER = 1;
    }

    /**
     * @method static static BOOL()
     * @method static static STRING()
     */
    class BoolAndString extends Enum {

        public const BOOL = true;
        public const STRING = '1';
    }

    /**
     * @method static static FLOAT()
     * @method static static INTEGER()
     */
    class FloatAndInteger extends Enum {

        public const FLOAT = 1.0;
        public const INTEGER = 1;
    }

    /**
     * @method static static FLOAT()
     * @method static static STRING()
     */
    class FloatAndString extends Enum {

        public const FLOAT = 1.0;
        public const STRING = '1';
    }

    /**
     * @method static static INTEGER()
     * @method static static STRING()
     */
    class IntegerAndString extends Enum {

        public const INTEGER = 1;
        public const STRING = '1';
    }
}