<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-06-22
 * Time: 23:54
 */

declare(strict_types=1);

namespace Gachakra\PhpEnum\Examples\BasicMethods;

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
}


/**
 * instance methods
 */
{
    assert(Continent::AFRICA()->name() === 'AFRICA');
    assert(Continent::AFRICA()->value() === 1);
    assert(Continent::AFRICA()->__toString() === '1');
}

/**
 * class methods
 */
{
    assert(Continent::of('AFRICA')->equals(Continent::AFRICA()));
    assert(Continent::fromValue(1)->equals(Continent::AFRICA()));
}


/**
 * class methods for array
 */
{
    assert(Continent::elements() === [
                    'AFRICA'        => Continent::AFRICA(),
                    'ASIA'          => Continent::ASIA(),
                    'EUROPE'        => Continent::EUROPE(),
                    'NORTH_AMERICA' => Continent::NORTH_AMERICA(),
                    'SOUTH_AMERICA' => Continent::SOUTH_AMERICA(),
                    'ANTARCTICA'    => Continent::ANTARCTICA(),
                    'AUSTRALIA'     => Continent::AUSTRALIA(),
            ]);

    assert(Continent::constants() === [
                    'AFRICA'        => 1,
                    'ASIA'          => 2,
                    'EUROPE'        => 3,
                    'NORTH_AMERICA' => 4,
                    'SOUTH_AMERICA' => 5,
                    'ANTARCTICA'    => 6,
                    'AUSTRALIA'     => 7,
            ]);

    assert(Continent::names() === [
                    'AFRICA',
                    'ASIA',
                    'EUROPE',
                    'NORTH_AMERICA',
                    'SOUTH_AMERICA',
                    'ANTARCTICA',
                    'AUSTRALIA'
            ]);

    assert(Continent::values() === [1, 2, 3, 4, 5, 6, 7]);

    assert(Continent::toStrings() === [
                    'AFRICA'        => '1',
                    'ASIA'          => '2',
                    'EUROPE'        => '3',
                    'NORTH_AMERICA' => '4',
                    'SOUTH_AMERICA' => '5',
                    'ANTARCTICA'    => '6',
                    'AUSTRALIA'     => '7',
            ]);
}