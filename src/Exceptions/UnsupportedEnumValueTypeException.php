<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-08-26
 * Time: 22:56
 */

declare(strict_types=1);


namespace Gachakra\PhpEnum\Exceptions;


use LogicException;

class UnsupportedEnumValueTypeException extends LogicException {

    /**
     * NotScalarEnumValueException constructor.
     * @param string $string
     */
    public function __construct(string $string) {
    }
}