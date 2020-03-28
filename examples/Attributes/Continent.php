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

    private const AFRICA        = 'Africa';
    private const ASIA          = 'Asia';
    private const EUROPE        = 'Europe';
    private const NORTH_AMERICA = 'North America';
    private const SOUTH_AMERICA = 'South America';
    private const ANTARCTICA    = 'Antarctica';
    private const AUSTRALIA     = 'Australia';

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
     * @param ArraySort $order
     * @return self[]
     */
    public static function sortByPopulation(?ArraySort $order = null): array {

        return array_map(function (string $continentValue) {
            return self::fromValue($continentValue);
        }, array_keys(ArraySort::byValue(self::$populations, $order)));
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

    public static function byValue(array $array, ?self $sort = null): array {

        ($sort ?? self::ASC())->equals(self::ASC())
                ? asort($array)
                : arsort($array);
        return $array;
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
}

/**
 * print
 */
{
    echo "\nPopulations of the Continents\n";
    foreach (Continent::sortByPopulation(ArraySort::DESC()) as $continent) {
        echo "    $continent: {$continent->formattedPopulation()}\n";
    }
    echo PHP_EOL;
}