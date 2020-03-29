<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2020/03/29
 * Time: 17:01
 */

declare(strict_types=1);

namespace Tests\BasicMethods;

use PHPUnit\Framework\TestCase;


class HasTest extends TestCase {

    /**
     * @test
     * @covers Enum::has()
     */
    public function return_true_if_has_name() {

        $this->assertTrue(SimpleEnum::has('ELEMENT1'));
        $this->assertTrue(SimpleEnum::has('ELEMENT2'));
    }

    /**
     * @test
     * @covers Enum::has()
     */
    public function return_false_if_not_have_name() {

        $this->assertFalse(SimpleEnum::has('element1'));
        $this->assertFalse(SimpleEnum::has('ELEMENT3'));
        $this->assertFalse(SimpleEnum::has(''));
    }

    /**
     * @test
     * @covers Enum::hasValue()
     */
    public function return_true_if_has_value() {

        $this->assertTrue(SimpleEnum::hasValue('element1'));
        $this->assertTrue(SimpleEnum::hasValue('element2'));
    }

    /**
     * @test
     * @covers Enum::hasValue()
     */
    public function return_false_if_not_have_value() {

        $this->assertFalse(SimpleEnum::hasValue('ELEMENT1'));
        $this->assertFalse(SimpleEnum::hasValue('element3'));
        $this->assertFalse(SimpleEnum::hasValue(''));

        $this->assertFalse(SimpleEnum::hasValue(0));
        $this->assertFalse(SimpleEnum::hasValue(1));
        $this->assertFalse(SimpleEnum::hasValue(true));
        $this->assertFalse(SimpleEnum::hasValue(false));
    }
}