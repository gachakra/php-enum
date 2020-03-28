<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-06-22
 * Time: 23:54
 */

declare(strict_types=1);

namespace Gachakra\PhpEnum\Examples\Properties;

require_once __DIR__ . "/../../vendor/autoload.php";

use Gachakra\PhpEnum\Enum;

/**
 * Class Continent
 *
 * @method static static AFRICA()
 * @method static static ASIA()
 * @method static static EUROPE()
 * @method static static NORTH_AMERICA()
 * @method static static SOUTH_AMERICA()
 * @method static static ANTARCTICA()
 * @method static static AUSTRALIA()
 */
final class Continent extends Enum {

    private const AFRICA        = 1;
    private const ASIA          = 2;
    private const EUROPE        = 3;
    private const NORTH_AMERICA = 4;
    private const SOUTH_AMERICA = 5;
    private const ANTARCTICA    = 6;
    private const AUSTRALIA     = 7;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $upperCamel;
    /**
     * @var string
     */
    private $lowerCamel;

    protected function __construct(int $value) {
        parent::__construct($value);

        $this->name = ucwords(strtr(strtolower($this->name()), ['_' => ' ']));
        $this->upperCamel = ucfirst(strtr($this->name, [' ' => '']));
        $this->lowerCamel = lcfirst($this->upperCamel);
    }

    /**
     * wrap to make context clearer
     *
     * @return int
     */
    public function id(): int {
        return $this->value();
    }

    /**
     * wrap to make context clearer
     *
     * @param int $id
     * @return Continent
     */
    public static function fromId(int $id): self {
        return self::fromValue($id);
    }

    public function asUpperCamel(): string {
        return $this->upperCamel;
    }

    public function asLowerCamel(): string {
        return $this->lowerCamel;
    }

    /**
     * @override
     */
    public function __toString(): string {
        return $this->name;
    }
}


{
    $continent = Continent::fromId(1);

    assert($continent->id() === 1);
    assert($continent->equals(Continent::AFRICA()));

    assert($continent->asUpperCamel() === 'Africa');
    assert($continent->asLowerCamel() === 'africa');
    assert("$continent" === 'Africa');
}

{
    $continent = Continent::fromId(5);

    assert($continent->id() === 5);
    assert($continent->equals(Continent::SOUTH_AMERICA()));

    assert($continent->asUpperCamel() === 'SouthAmerica');
    assert($continent->asLowerCamel() === 'southAmerica');
    assert("$continent" === 'South America');
}