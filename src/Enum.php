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
 * - element: Enum instance
 * - name   : const name
 * - value  : const value
 *
 * CONTRACT
 * - only `scalar` and `null` value allowed as Enum subclass const value (`array` not allowed)
 * - duplicated consts values are not allowed in an Enum
 * - all the consts values must be the same type
 *
 * RECOMMENDATION
 * - subclass must have the constructor whose first parameter is scalar type
 * - declare `final` in subclass
 * - make subclass's consts and constructor `protected` or `private`
 *
 * COMPARISON
 * - use @see Enum::equals() to compare 2 Enums to avoid mistaking like using `===` in code
 *
 * Class Enum
 * @package Enum
 */
abstract class Enum {

    private static $constants = [];

    private static $instances = [];

    private static $names = [];

    private static $values = [];

    private $name;

    private $scalar;

    /**
     * @param $name
     * @param $args
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
     * @param bool|float|int|string $value
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
     * @param self $other
     * @return bool
     */
    public final function equals(self $other): bool {
        return $this == $other;
    }

    /**
     * @param string $name
     * @return Enum
     */
    public final static function of(string $name): self {
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        if (static::has($name)) {
            return static::elements()[$name];
        }

        throw new DomainException("Unknown Enum name: $enum::$name");
    }

    /**
     * @param $value
     * @return Enum
     */
    public final static function fromValue($value): self {
        self::checkIfCalledNotViaRootEnum(static::class);

        return static::of(self::nameByValue($value));
    }

    /**
     * ex:
     * ["CONST_NAME" => $ENUM_INSTANCE]
     *
     * @return static[]
     */
    public final static function elements(): array {
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        if (!empty(self::$instances[$enum])) {
            return self::$instances[$enum];
        }

        $values = [];
        foreach (static::constants() as $name => $value) {
            $values[$name] = new static($value);
        }
        return self::$instances[$enum] = $values;
    }

    /**
     * ex:
     * ["CONST_NAME" => $scalar]
     *
     * @return array
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
     * @return array
     */
    public final static function names(): array {
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        return empty(self::$names[$enum])
                ? self::$names[$enum] = array_keys(static::constants())
                : self::$names[$enum];    }

    /**
     * ex:
     * [$scalar]
     *
     * @return array|$scalar[]
     */
    public final static function values(): array {
        self::checkIfCalledNotViaRootEnum($enum = static::class);

        return empty(self::$values[$enum])
                ? self::$values[$enum] = array_values(static::constants())
                : self::$values[$enum];
    }

    /**
     * @param string $name
     * @return bool
     */
    public final static function has(string $name): bool {
        return array_key_exists($name, static::constants());
    }

    /**
     * @param $value
     * @return string
     */
    private static function nameByValue($value): string {
        self::checkIfValueIsScalar($value);

        $name = array_search($value, static::constants(), true);
        if ($name === false) {
            $enum = get_called_class();
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
        if ($calledEnum === __CLASS__) {
            throw new RootEnumMethodCallException("Cannot call static method directly via root abstract Enum");
        }
    }

    /**
     * @param $value
     */
    private static function checkIfValueIsScalar($value): void {
        if (!(is_null($value) || is_scalar($value))) {
            $type = gettype($value);
            throw new InvalidArgumentException("Enum value must be scalar, but $type given");
        }
    }

    /**
     * @param array $constants
     */
    private static function checkIfValuesNotDuplicated(array $constants): void {
        if (count($constants) !== count(array_unique($constants))) {
            throw new DuplicateEnumValueException("Each Enum constant must have a different value");
        }
    }

    /**
     * null type allowed
     *
     * @param array $constants
     */
    private static function checkIfAllValuesSameType(array $constants): void {
        $previousType = null;
        foreach ($constants as $value) {
            $currentType = gettype($value);
            if (is_null($previousType) || $currentType === $previousType) {
                $previousType = $currentType;
                continue;
            }

            throw new MultipleEnumValueTypeException("All the Enum constants values must be same type");
        }
    }
}

