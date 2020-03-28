<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2018/10/19
 * Time: 23:15
 */

declare(strict_types=1);

namespace Gachakra\PhpEnum;

use BadMethodCallException;
use Gachakra\PhpEnum\Exceptions\DuplicateEnumValueException;
use Gachakra\PhpEnum\Exceptions\EnumDomainException;
use Gachakra\PhpEnum\Exceptions\MultipleEnumValueTypeException;
use Gachakra\PhpEnum\Exceptions\RootEnumMethodCallException;
use Gachakra\PhpEnum\Exceptions\UnsupportedEnumValueTypeException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * DEFINITION
 * - element  : Enum instance
 * - constant : const itself
 * - name     : const name (equally, Enum instance name)
 * - value    : const value
 *
 * CONTRACT
 * - only `scalar` and `null` value allowed as Enum subclass const value (`array` not allowed so far)
 * - duplicated consts' values are not allowed in an Enum
 * - all the consts' values must be the same type
 *
 * RECOMMENDATION
 * - make the first parameter the same type as consts' value when subclass has the constructor
 * - declare `final` in subclass
 * - make subclass's consts `protected` or `private`
 * - make subclass's constructor `protected`
 *
 * COMPARISON
 * - use @see Enum::equals() to compare 2 Enums to avoid mistaking like using `===` in code
 *
 * Class Enum
 * @package Enum
 */
abstract class Enum {

    /**
     * @var string[] => static[]
     */
    private static $elements = [];
    /**
     * @var string[] => bool[]|float[]|int[]|string[]
     */
    private static $constants = [];
    /**
     * @var string[] => string[]
     */
    private static $names = [];
    /**
     * @var string[] => bool[]|float[]|int[]|string[]
     */
    private static $values = [];
    /**
     * @var string[] => string[]
     */
    private static $strings = [];

    /**
     * @var string
     */
    private $name;
    /**
     * @var bool|float|int|string|null
     */
    private $scalar;

    /**
     * @param string $name
     * @param        $args
     * @return static
     */
    public final static function __callStatic(string $name, array $args): self {
        self::throwIfCalledViaRootEnum();

        if (!empty($args)) {
            throw new BadMethodCallException("No arguments required for Enum, but given");
        }

        return static::of($name);
    }

    /**
     * Enum constructor.
     * @param bool|float|int|string|null $value
     */
    protected function __construct($value) {
        $this->name = self::nameByValue($value);
        $this->scalar = $value;
    }

    /**
     * @return string
     */
    public final function name(): string {
        return $this->name;
    }

    /**
     * @return bool|float|int|string|null
     */
    public final function value() {
        return $this->scalar;
    }

    /**
     * able to be overridden
     * @return string
     */
    public function __toString(): string {
        return (string)$this->scalar;
    }

    /**
     * @param static $other
     * @return bool
     */
    public final function equals(self $other): bool {
        return $this == $other;
    }

    /**
     * @param string $name
     * @return static
     */
    public final static function of(string $name): self {
        self::throwIfCalledViaRootEnum();

        return static::elements()[$name]
                ?? self::throwAgainstUnknownName($name);
    }

    /**
     * @param bool|float|int|string|null $value
     * @return static
     */
    public final static function fromValue($value): self {
        self::throwIfCalledViaRootEnum();

        return static::of(self::nameByValue($value));
    }

    /**
     * [const1_name => enum_instance_of_const1, const2_name => enum_instance_of_const2...]
     * @return static[]
     */
    public final static function elements(): array {
        self::throwIfCalledViaRootEnum();

        if (!empty(self::$elements[$enum = static::class])) {
            return self::$elements[$enum];
        }

        $values = [];
        foreach (static::constants() as $name => $value) {
            $values[$name] = new static($value);
        }
        return self::$elements[$enum] = $values;
    }

    /**
     * [const1_name => const1_value, const2_name => const2_value...]
     * @return bool[]|float[]|int[]|string[]
     */
    public final static function constants(): array {
        self::throwIfCalledViaRootEnum();

        if (!empty(self::$constants[$enum = static::class])) {
            return self::$constants[$enum];
        }

        try {
            $constants = (new ReflectionClass($enum))->getConstants();
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        self::checkConstantValuesValidity($constants);
        return self::$constants[$enum] = $constants;
    }

    /**
     * [const1_name, const2_name...]
     * @return string[]
     */
    public final static function names(): array {
        self::throwIfCalledViaRootEnum();

        return self::$names[$enum = static::class]
                ?? self::$names[$enum] = array_keys(static::constants());
    }

    /**
     * [const1_value, const2_value...]
     * @return bool[]|float[]|int[]|string[]
     */
    public final static function values(): array {
        self::throwIfCalledViaRootEnum();

        return self::$values[$enum = static::class]
                ?? self::$values[$enum] = array_values(static::constants());
    }

    /**
     * [const1_value => enum_instance_of_const1, const2_value => enum_instance_of_const2...]
     * @return static[]
     */
    public final static function valueToElement(): array {
        self::throwIfCalledViaRootEnum();

        $valueToElements = [];
        foreach (static::elements() as $element) {
            $valueToElements[$element->value()] = $element;
        }
        return $valueToElements;
    }

    /**
     * [const1_name => const1_toString_value, const2_name => const2_toString_value...]
     * @return string[]
     */
    public final static function toStrings(): array {
        self::throwIfCalledViaRootEnum();

        return self::$strings[$enum = static::class]
                ?? self::$strings[$enum] = array_map('strval', self::constants());
    }

    /**
     * @param string $name
     * @return bool
     */
    public final static function has(string $name): bool {
        self::throwIfCalledViaRootEnum();

        return array_key_exists($name, static::constants());
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public final static function hasValue($value): bool {
        self::throwIfCalledViaRootEnum();

        return in_array($value, static::constants(), true);
    }

    /**
     * @param bool|float|int|string|null $value
     * @return string
     */
    private static function nameByValue($value): string {
        self::throwAgainstUnsupportedValueType($value);

        $name = array_search($value, static::constants(), true);

        $name === false && self::throwAgainstUnknownValue($value);

        return $name;
    }

    /**
     * check all values of an enum are
     * - scalar or null
     * - the same type except null
     * - unique
     *
     * @param bool[]|float[]|int[]|string[] $constants
     */
    private static function checkConstantValuesValidity(array $constants): void {
        $uniqueValues = [];
        $previousType = null;
        foreach ($constants as $value) {
            if (in_array($value, $uniqueValues, true)) {
                throw new DuplicateEnumValueException("Each Enum constant must have a different value");
            }

            if (is_null($value)) {
                $uniqueValues[] = $value;
                continue;
            }

            $currentType = gettype($value);
            if (!is_scalar($value)) {
                throw new UnsupportedEnumValueTypeException("Enum value must be scalar or null, but $currentType given");
            }

            if ($currentType !== $previousType && !is_null($previousType)) {
                throw new MultipleEnumValueTypeException("All the Enum constants values must be the same type");
            }

            $uniqueValues[] = $value;
            $previousType = $currentType;
        }
    }

    /**
     * check callee class to avoid public static methods called directly from this class
     * like Enum::__callStatic(), Enum::values() or so
     */
    private static function throwIfCalledViaRootEnum(): void {
        if (static::class === self::class) {
            throw new RootEnumMethodCallException("Cannot call static method directly via root abstract Enum");
        }
    }

    private static function throwAgainstUnsupportedValueType($value): void {
        if (is_scalar($value) || is_null($value)) {
            return;
        }
        $type = gettype($value);
        throw new UnsupportedEnumValueTypeException("Enum value must be scalar or null, but $type given");
    }

    private static function throwAgainstUnknownName(string $name): void {
        $enum = static::class;
        throw new EnumDomainException("Unknown Enum name: [$enum::$name]");
    }

    private static function throwAgainstUnknownValue($value): void {
        $enum = static::class;
        throw new EnumDomainException("Unknown Enum value in $enum: [$value]");
    }
}

