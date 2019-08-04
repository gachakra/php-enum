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
use DomainException;
use Gachakra\PhpEnum\Exceptions\DuplicateEnumValueException;
use Gachakra\PhpEnum\Exceptions\MultipleEnumValueTypeException;
use Gachakra\PhpEnum\Exceptions\RootEnumMethodCallException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * DEFINITION
 * - element  : Enum instance
 * - constant : const itself
 * - name     : const name
 * - value    : const value
 *
 * CONTRACT
 * - only `scalar` and `null` value allowed as Enum subclass const value (`array` not allowed)
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
     * @var static[]
     */
    private static $elements = [];
    /**
     * @var bool[]|float[]|int[]|string[]
     */
    private static $constants = [];
    /**
     * @var string[]
     */
    private static $names = [];
    /**
     * @var bool[]|float[]|int[]|string[]
     */
    private static $values = [];

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
    public final static function __callStatic($name, array $args): self {
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        if (!empty($args)) {
            throw new BadMethodCallException("No arguments required for Enum, but given");
        }

        if (!static::has($name)) {
            throw new DomainException("Unknown Enum name: $enum::$name");
        }

        return static::elements()[$name];
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
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        if (static::has($name)) {
            return static::elements()[$name];
        }

        throw new DomainException("Unknown Enum name: $enum::$name");
    }

    /**
     * @param bool|float|int|string|null $value
     * @return static
     */
    public final static function fromValue($value): self {
        self::checkIfCalledNotViaRootEnum(static::class);

        return static::of(self::nameByValue($value));
    }

    /**
     * [const1_name => enum_instance_of_const1, const2_name => enum_instance_of_const2...]
     * @return static[]
     */
    public final static function elements(): array {
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        if (!empty(self::$elements[$enum])) {
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
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        if (!empty(self::$constants[$enum])) {
            return self::$constants[$enum];
        }

        try {
            $constants = (new ReflectionClass($enum))->getConstants();
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        self::checkIfValuesNotDuplicated($constants);
        self::checkIfAllValuesSameType($constants);

        return self::$constants[$enum] = $constants;
    }

    /**
     * [const1_name, const2_name...]
     * @return string[]
     */
    public final static function names(): array {
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        return self::$names[$enum]
                ?? self::$names[$enum] = array_keys(static::constants());
    }

    /**
     * [const1_value, const2_value...]
     * @return bool[]|float[]|int[]|string[]
     */
    public final static function values(): array {
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        return self::$values[$enum]
                ?? self::$values[$enum] = array_values(static::constants());
    }

    /**
     * @param string $name
     * @return bool
     */
    public final static function has(string $name): bool {
        return array_key_exists($name, static::constants());
    }

    /**
     * @param bool|float|int|string|null $value
     * @return string
     */
    private static function nameByValue($value): string {
        self::checkIfValueIsScalar($value);

        $name = array_search($value, static::constants(), true);
        if ($name === false) {
            $enum = static::class;
            throw new DomainException("Unknown Enum value in $enum: $value");
        }
        return $name;
    }

    /**
     * check callee class to avoid public static methods called directly by this class
     * like Enum::__callStatic(), Enum::values() or so
     * @param string $calledEnum
     */
    private static function checkIfCalledNotViaRootEnum(string $calledEnum): void {
        if ($calledEnum === self::class) {
            throw new RootEnumMethodCallException("Cannot call static method directly via root abstract Enum");
        }
    }

    /**
     * @param bool|float|int|string|null $value
     */
    private static function checkIfValueIsScalar($value): void {
        if (!(is_null($value) || is_scalar($value))) {
            $type = gettype($value);
            throw new InvalidArgumentException("Enum value must be scalar, but $type given");
        }
    }

    /**
     * @param bool[]|float[]|int[]|string[] $constants
     */
    private static function checkIfValuesNotDuplicated(array $constants): void {
        if (count($constants) !== count(array_unique($constants))) {
            throw new DuplicateEnumValueException("Each Enum constant must have a different value");
        }
    }

    /**
     * null type allowed
     *
     * @param bool[]|float[]|int[]|string[] $constants
     */
    private static function checkIfAllValuesSameType(array $constants): void {
        $previousType = null;
        foreach ($constants as $value) {
            $currentType = gettype($value);
            if (is_null($previousType) || $currentType === $previousType) {
                $previousType = $currentType;
                continue;
            }

            throw new MultipleEnumValueTypeException("All the Enum constants values must be the same type");
        }
    }
}

