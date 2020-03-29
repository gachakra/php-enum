<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2020/03/29
 * Time: 17:00
 */

declare(strict_types=1);

namespace Tests\BasicMethods;

use Gachakra\PhpEnum\Enum;

/**
 * Class SimpleEnum
 * @package Tests
 *
 * @method static self ELEMENT1
 * @method static self ELEMENT2
 */
class SimpleEnum extends Enum {

    const ELEMENT1 = 'element1';
    const ELEMENT2 = 'element2';

    /**
     * SimpleEnum constructor.
     * @param string $value
     */
    public function __construct(string $value) {
        parent::__construct($value);
    }
}
