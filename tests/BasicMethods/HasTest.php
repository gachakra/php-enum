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

        $this->assertTrue(EnumWithPublicConstructor::has('ELEMENT1'));
        $this->assertTrue(EnumWithPublicConstructor::has('ELEMENT2'));
    }

    /**
     * @test
     * @covers Enum::has()
     */
    public function return_false_if_not_have_name() {

        $this->assertFalse(EnumWithPublicConstructor::has('element1'));
        $this->assertFalse(EnumWithPublicConstructor::has('ELEMENT3'));
        $this->assertFalse(EnumWithPublicConstructor::has(''));
    }

    /**
     * @test
     * @covers Enum::hasValue()
     */
    public function return_true_if_has_value() {

        $this->assertTrue(EnumWithPublicConstructor::hasValue('element1'));
        $this->assertTrue(EnumWithPublicConstructor::hasValue('element2'));
    }

    /**
     * @test
     * @covers Enum::hasValue()
     */
    public function return_false_if_not_have_value() {

        $this->assertFalse(EnumWithPublicConstructor::hasValue('ELEMENT1'));
        $this->assertFalse(EnumWithPublicConstructor::hasValue('element3'));
        $this->assertFalse(EnumWithPublicConstructor::hasValue(''));

        $this->assertFalse(EnumWithPublicConstructor::hasValue(0));
        $this->assertFalse(EnumWithPublicConstructor::hasValue(1));
        $this->assertFalse(EnumWithPublicConstructor::hasValue(true));
        $this->assertFalse(EnumWithPublicConstructor::hasValue(false));
    }
}