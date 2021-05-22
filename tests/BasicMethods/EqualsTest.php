<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2020/03/29
 * Time: 16:42
 */

declare(strict_types=1);

namespace Tests\BasicMethods;

use PHPUnit\Framework\TestCase;

class EqualsTest extends TestCase {

    /**
     * @var EnumWithPublicConstructor
     */
    private $baseInstance1;
    /**
     * @var EnumWithPublicConstructor
     */
    private $baseInstance2;

    protected function setUp(): void {
        parent::setUp();

        $this->baseInstance1 = EnumWithPublicConstructor::ELEMENT1();
        $this->baseInstance2 = EnumWithPublicConstructor::ELEMENT2();
    }

    /**
     * @test
     * @covers Enum::equals()
     */
    public function equals_to_the_same_element_instances_in_the_same_enum() {

        $this->assertTrue($this->baseInstance1->equals($this->baseInstance1));
        $this->assertTrue($this->baseInstance1->equals(EnumWithPublicConstructor::ELEMENT1()));

        $this->assertTrue($this->baseInstance1->equals(EnumWithPublicConstructor::of('ELEMENT1')));
        $this->assertTrue($this->baseInstance1->equals(EnumWithPublicConstructor::fromValue('element1')));

        $this->assertTrue($this->baseInstance1->equals(EnumWithPublicConstructor::elements()['ELEMENT1']));
        $this->assertTrue($this->baseInstance1->equals(EnumWithPublicConstructor::valueToElement()['element1']));

        $this->assertTrue($this->baseInstance1->equals(new EnumWithPublicConstructor(EnumWithPublicConstructor::ELEMENT1)));
    }

    /**
     * @test
     * @covers Enum::equals()
     */
    public function not_equal_to_the_other_element_instances_in_the_same_enum() {

        // 1 to 2
        {
            $this->assertFalse($this->baseInstance1->equals($this->baseInstance2));
            $this->assertFalse($this->baseInstance1->equals(EnumWithPublicConstructor::ELEMENT2()));

            $this->assertFalse($this->baseInstance1->equals(EnumWithPublicConstructor::of('ELEMENT2')));
            $this->assertFalse($this->baseInstance1->equals(EnumWithPublicConstructor::fromValue('element2')));

            $this->assertFalse($this->baseInstance1->equals(EnumWithPublicConstructor::elements()['ELEMENT2']));
            $this->assertFalse($this->baseInstance1->equals(EnumWithPublicConstructor::valueToElement()['element2']));

            $this->assertFalse($this->baseInstance1->equals(new EnumWithPublicConstructor(EnumWithPublicConstructor::ELEMENT2)));
        }

        // 2 to 1
        {
            $this->assertFalse($this->baseInstance2->equals($this->baseInstance1));
            $this->assertFalse($this->baseInstance2->equals(EnumWithPublicConstructor::ELEMENT1()));

            $this->assertFalse($this->baseInstance2->equals(EnumWithPublicConstructor::of('ELEMENT1')));
            $this->assertFalse($this->baseInstance2->equals(EnumWithPublicConstructor::fromValue('element1')));

            $this->assertFalse($this->baseInstance2->equals(EnumWithPublicConstructor::elements()['ELEMENT1']));
            $this->assertFalse($this->baseInstance2->equals(EnumWithPublicConstructor::valueToElement()['element1']));

            $this->assertFalse($this->baseInstance2->equals(new EnumWithPublicConstructor(EnumWithPublicConstructor::ELEMENT1)));
        }
    }

    /**
     * @test
     * @covers Enum::equals()
     */
    public function not_equal_to_the_same_name_value_instances_in_another_enum() {
        
            $this->assertFalse($this->baseInstance1->equals(EnumWithProtectedConstructor::ELEMENT1()));

            $this->assertFalse($this->baseInstance1->equals(EnumWithProtectedConstructor::of('ELEMENT1')));
            $this->assertFalse($this->baseInstance1->equals(EnumWithProtectedConstructor::fromValue('element1')));

            $this->assertFalse($this->baseInstance1->equals(EnumWithProtectedConstructor::elements()['ELEMENT1']));
            $this->assertFalse($this->baseInstance1->equals(EnumWithProtectedConstructor::valueToElement()['element1']));
    }
}