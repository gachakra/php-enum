<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-06-22
 * Time: 23:54
 */

declare(strict_types=1);

namespace Gachakra\PhpEnum\Examples\Attributes;

require_once __DIR__ . "/../../vendor/autoload.php";

use DomainException;
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

    public const AFRICA        = 'Africa';
    public const ASIA          = 'Asia';
    public const EUROPE        = 'Europe';
    public const NORTH_AMERICA = 'North America';
    public const SOUTH_AMERICA = 'South America';
    public const ANTARCTICA    = 'Antarctica';
    public const AUSTRALIA     = 'Australia';

    private static $populations = [
            self::AFRICA        => 1287920000,
            self::ASIA          => 4545133000,
            self::EUROPE        => 742648000,
            self::NORTH_AMERICA => 587615000,
            self::SOUTH_AMERICA => 428240000,
            self::ANTARCTICA    => 4490,
            self::AUSTRALIA     => 41261000,
    ];

    public function population(): int {
        return self::$populations["$this"]; // or self::$populations[$this->value()];
    }

    public function formattedPopulation(): string {
        return number_format($this->population());
    }

    public static function populationOf(self $continent): int {
        if (!isset(self::$populations["$continent"])) {
            throw new DomainException();
        }
        return self::$populations["$continent"]; // or self::$populations[$continent->value()];
    }

    /**
     * @param ArraySort $sort
     * @return self[]
     */
    public static function sortByPopulation(?ArraySort $sort = null): array {

        return array_map(function (string $continentValue) {
            return self::fromValue($continentValue);
        }, array_keys(($sort ?? ArraySort::ASC())->byValue(self::$populations)));
    }

    public static function sortByName(?ArraySort $sort = null): array {
        return ($sort ?? ArraySort::ASC())->byKey(self::valueToElement());
    }
}

/**
 * Class ArraySort
 *
 * @method static static ASC()
 * @method static static DESC()
 */
final class ArraySort extends Enum {

    private const ASC  = 'asc';
    private const DESC = 'desc';

    public static function byValueOrder(array $array, ?self $sort = null): array {

        ($sort ?? self::ASC())->equals(self::ASC())
                ? asort($array)
                : arsort($array);
        return $array;
    }

    public static function byKeyOrder(array $array, ?self $sort = null): array {

        ($sort ?? self::ASC())->equals(self::ASC())
                ? ksort($array)
                : krsort($array);
        return $array;
    }

    public function byValue(array $array): array {
        return self::byValueOrder($array, $this);
    }

    public function byKey(array $array): array {
        return self::byKeyOrder($array, $this);
    }
}


/**
 * accessors
 */
{
    assert(Continent::AFRICA()->population() === 1287920000);
    assert(Continent::AFRICA()->formattedPopulation() === '1,287,920,000');

    assert(Continent::populationOf(Continent::ANTARCTICA()) === 4490);
}

/**
 * sort
 */
{
    assert(Continent::sortByPopulation() === [
                    Continent::ANTARCTICA(),
                    Continent::AUSTRALIA(),
                    Continent::SOUTH_AMERICA(),
                    Continent::NORTH_AMERICA(),
                    Continent::EUROPE(),
                    Continent::AFRICA(),
                    Continent::ASIA()
            ]);

    assert(Continent::sortByPopulation(ArraySort::DESC()) === [
                    Continent::ASIA(),
                    Continent::AFRICA(),
                    Continent::EUROPE(),
                    Continent::NORTH_AMERICA(),
                    Continent::SOUTH_AMERICA(),
                    Continent::AUSTRALIA(),
                    Continent::ANTARCTICA()
            ]);

    assert(Continent::sortByName() == [
                    Continent::AFRICA        => Continent::AFRICA(),
                    Continent::ANTARCTICA    => Continent::ANTARCTICA(),
                    Continent::ASIA          => Continent::ASIA(),
                    Continent::AUSTRALIA     => Continent::AUSTRALIA(),
                    Continent::EUROPE        => Continent::EUROPE(),
                    Continent::NORTH_AMERICA => Continent::NORTH_AMERICA(),
                    Continent::SOUTH_AMERICA => Continent::SOUTH_AMERICA(),
            ]);


    assert(Continent::sortByName(ArraySort::DESC()) === [
                    Continent::SOUTH_AMERICA => Continent::SOUTH_AMERICA(),
                    Continent::NORTH_AMERICA => Continent::NORTH_AMERICA(),
                    Continent::EUROPE        => Continent::EUROPE(),
                    Continent::AUSTRALIA     => Continent::AUSTRALIA(),
                    Continent::ASIA          => Continent::ASIA(),
                    Continent::ANTARCTICA    => Continent::ANTARCTICA(),
                    Continent::AFRICA        => Continent::AFRICA()
            ]);
}

/**
 * print
 */
{
    echo "\nPopulations of the Continents (descending order)\n";
    foreach (Continent::sortByPopulation(ArraySort::DESC()) as $continent) {
        echo "    $continent: {$continent->formattedPopulation()}\n";
    }
    echo PHP_EOL;
}