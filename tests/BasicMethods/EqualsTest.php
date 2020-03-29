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
     * @var SimpleEnum
     */
    private $baseInstance1;
    /**
     * @var SimpleEnum
     */
    private $baseInstance2;

    protected function setUp(): void {
        parent::setUp();

        $this->baseInstance1 = SimpleEnum::ELEMENT1();
        $this->baseInstance2 = SimpleEnum::ELEMENT2();
    }

    /**
     * @test
     * @covers Enum::equals()
     */
    public function equals_to_the_same_element_instances_created_in_any_ways() {

        $this->assertTrue($this->baseInstance1->equals($this->baseInstance1));
        $this->assertTrue($this->baseInstance1->equals(SimpleEnum::ELEMENT1()));

        $this->assertTrue($this->baseInstance1->equals(SimpleEnum::of('ELEMENT1')));
        $this->assertTrue($this->baseInstance1->equals(SimpleEnum::fromValue('element1')));

        $this->assertTrue($this->baseInstance1->equals(SimpleEnum::elements()['ELEMENT1']));
        $this->assertTrue($this->baseInstance1->equals(SimpleEnum::valueToElement()['element1']));

        $this->assertTrue($this->baseInstance1->equals(new SimpleEnum(SimpleEnum::ELEMENT1)));
    }

    /**
     * @test
     * @covers Enum::equals()
     */
    public function not_equal_to_the_other_element_instances_created_in_any_ways() {

        // 1 to 2
        {
            $this->assertFalse($this->baseInstance1->equals($this->baseInstance2));
            $this->assertFalse($this->baseInstance1->equals(SimpleEnum::ELEMENT2()));

            $this->assertFalse($this->baseInstance1->equals(SimpleEnum::of('ELEMENT2')));
            $this->assertFalse($this->baseInstance1->equals(SimpleEnum::fromValue('element2')));

            $this->assertFalse($this->baseInstance1->equals(SimpleEnum::elements()['ELEMENT2']));
            $this->assertFalse($this->baseInstance1->equals(SimpleEnum::valueToElement()['element2']));

            $this->assertFalse($this->baseInstance1->equals(new SimpleEnum(SimpleEnum::ELEMENT2)));
        }

        // 2 to 1
        {
            $this->assertFalse($this->baseInstance2->equals($this->baseInstance1));
            $this->assertFalse($this->baseInstance2->equals(SimpleEnum::ELEMENT1()));

            $this->assertFalse($this->baseInstance2->equals(SimpleEnum::of('ELEMENT1')));
            $this->assertFalse($this->baseInstance2->equals(SimpleEnum::fromValue('element1')));

            $this->assertFalse($this->baseInstance2->equals(SimpleEnum::elements()['ELEMENT1']));
            $this->assertFalse($this->baseInstance2->equals(SimpleEnum::valueToElement()['element1']));

            $this->assertFalse($this->baseInstance2->equals(new SimpleEnum(SimpleEnum::ELEMENT1)));
        }
    }
}