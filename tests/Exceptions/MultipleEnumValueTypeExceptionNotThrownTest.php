<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-08-04
 * Time: 16:59
 */

namespace Tests\Exceptions;


use Gachakra\PhpEnum\Enum;
use Generator;
use PHPUnit\Framework\TestCase;

class MultipleEnumValueTypeExceptionNotThrownTest extends TestCase {

    /**
     * @test
     * @doesNotPerformAssertions
     * @dataProvider provideNullContainedValues
     *
     * @param string                     $enumFqcn
     * @param bool|float|int|string|null $value
     */
    public function not_thrown_even_even_if_enum_has_null(string $enumFqcn, $value) {

        $enumFqcn::fromValue($value);
    }

    /**
     * @return Generator|string[]
     */
    public function provideNullContainedValues(): Generator {

        {
            yield [StringAndNullAtFirst::class, StringAndNullAtFirst::NULL];
            yield [StringAndNullAtFirst::class, StringAndNullAtFirst::VALUE1];
            yield [StringAndNullAtFirst::class, StringAndNullAtFirst::VALUE2];

            yield [StringAndNullInMiddle::class, StringAndNullInMiddle::VALUE1];
            yield [StringAndNullInMiddle::class, StringAndNullInMiddle::NULL];
            yield [StringAndNullInMiddle::class, StringAndNullInMiddle::VALUE2];

            yield [StringAndNullAtLast::class, StringAndNullAtLast::VALUE1];
            yield [StringAndNullAtLast::class, StringAndNullAtLast::VALUE2];
            yield [StringAndNullAtLast::class, StringAndNullAtLast::NULL];
        }

        {
            yield [IntegerAndNullAtFirst::class, IntegerAndNullAtFirst::NULL];
            yield [IntegerAndNullAtFirst::class, IntegerAndNullAtFirst::VALUE1];
            yield [IntegerAndNullAtFirst::class, IntegerAndNullAtFirst::VALUE2];

            yield [IntegerAndNullInMiddle::class, IntegerAndNullInMiddle::VALUE1];
            yield [IntegerAndNullInMiddle::class, IntegerAndNullInMiddle::NULL];
            yield [IntegerAndNullInMiddle::class, IntegerAndNullInMiddle::VALUE2];

            yield [IntegerAndNullAtLast::class, IntegerAndNullAtLast::VALUE1];
            yield [IntegerAndNullAtLast::class, IntegerAndNullAtLast::VALUE2];
            yield [IntegerAndNullAtLast::class, IntegerAndNullAtLast::NULL];
        }

        {
            yield [FloatAndNullAtFirst::class, FloatAndNullAtFirst::NULL];
            yield [FloatAndNullAtFirst::class, FloatAndNullAtFirst::VALUE1];
            yield [FloatAndNullAtFirst::class, FloatAndNullAtFirst::VALUE2];

            yield [FloatAndNullInMiddle::class, FloatAndNullInMiddle::VALUE1];
            yield [FloatAndNullInMiddle::class, FloatAndNullInMiddle::NULL];
            yield [FloatAndNullInMiddle::class, FloatAndNullInMiddle::VALUE2];

            yield [FloatAndNullAtLast::class, FloatAndNullAtLast::VALUE1];
            yield [FloatAndNullAtLast::class, FloatAndNullAtLast::VALUE2];
            yield [FloatAndNullAtLast::class, FloatAndNullAtLast::NULL];
        }

        {
            yield [BoolAndNullAtFirst::class, BoolAndNullAtFirst::NULL];
            yield [BoolAndNullAtFirst::class, BoolAndNullAtFirst::VALUE1];
            yield [BoolAndNullAtFirst::class, BoolAndNullAtFirst::VALUE2];

            yield [BoolAndNullInMiddle::class, BoolAndNullInMiddle::VALUE1];
            yield [BoolAndNullInMiddle::class, BoolAndNullInMiddle::NULL];
            yield [BoolAndNullInMiddle::class, BoolAndNullInMiddle::VALUE2];

            yield [BoolAndNullAtLast::class, BoolAndNullAtLast::VALUE1];
            yield [BoolAndNullAtLast::class, BoolAndNullAtLast::VALUE2];
            yield [BoolAndNullAtLast::class, BoolAndNullAtLast::NULL];
        }
    }
}

{
    /**
     * @method static static NULL()
     * @method static static VALUE1()
     * @method static static VALUE2()
     */
    class StringAndNullAtFirst extends Enum {

        public const NULL = null;
        public const VALUE1 = '0';
        public const VALUE2 = '1';
    }

    /**
     * @method static static VALUE1()
     * @method static static NULL()
     * @method static static VALUE2()
     */
    class StringAndNullInMiddle extends Enum {

        public const VALUE1 = '0';
        public const NULL = null;
        public const VALUE2 = '1';
    }

    /**
     * @method static static VALUE1()
     * @method static static VALUE2()
     * @method static static NULL()
     */
    class StringAndNullAtLast extends Enum {

        public const VALUE1 = '0';
        public const VALUE2 = '1';
        public const NULL = null;
    }
}

{
    /**
     * @method static static NULL()
     * @method static static VALUE1()
     * @method static static VALUE2()
     */
    class IntegerAndNullAtFirst extends Enum {

        public const NULL = null;
        public const VALUE1 = 0;
        public const VALUE2 = 1;
    }

    /**
     * @method static static VALUE1()
     * @method static static NULL()
     * @method static static VALUE2()
     */
    class IntegerAndNullInMiddle extends Enum {

        public const VALUE1 = 0;
        public const NULL = null;
        public const VALUE2 = 1;
    }

    /**
     * @method static static VALUE1()
     * @method static static VALUE2()
     * @method static static NULL()
     */
    class IntegerAndNullAtLast extends Enum {

        public const VALUE1 = 0;
        public const VALUE2 = 1;
        public const NULL = null;
    }
}

{
    /**
     * @method static static NULL()
     * @method static static VALUE1()
     * @method static static VALUE2()
     */
    class FloatAndNullAtFirst extends Enum {

        public const NULL = null;
        public const VALUE1 = 0.0;
        public const VALUE2 = 1.0;
    }

    /**
     * @method static static VALUE1()
     * @method static static NULL()
     * @method static static VALUE2()
     */
    class FloatAndNullInMiddle extends Enum {

        public const VALUE1 = 0.0;
        public const NULL = null;
        public const VALUE2 = 1.0;
    }

    /**
     * @method static static VALUE1()
     * @method static static VALUE2()
     * @method static static NULL()
     */
    class FloatAndNullAtLast extends Enum {

        public const VALUE1 = 0.0;
        public const VALUE2 = 1.0;
        public const NULL = null;
    }
}

{
    /**
     * @method static static NULL()
     * @method static static VALUE1()
     * @method static static VALUE2()
     */
    class BoolAndNullAtFirst extends Enum {

        public const NULL = null;
        public const VALUE1 = false;
        public const VALUE2 = true;
    }

    /**
     * @method static static VALUE1()
     * @method static static NULL()
     * @method static static VALUE2()
     */
    class BoolAndNullInMiddle extends Enum {

        public const VALUE1 = false;
        public const NULL = null;
        public const VALUE2 = true;
    }

    /**
     * @method static static VALUE1()
     * @method static static VALUE2()
     * @method static static NULL()
     */
    class BoolAndNullAtLast extends Enum {

        public const VALUE1 = false;
        public const VALUE2 = true;
        public const NULL = null;
    }
}