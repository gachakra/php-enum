<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-08-05
 * Time: 20:32
 */

declare(strict_types=1);

require_once __DIR__ . "/../../vendor/autoload.php";

use Gachakra\PhpEnum\Enum;

/**
 * @method static static SPECIAL_ROOM()
 * @method static static GENERAL_ROOM()
 */
abstract class RoomPriceTypeBase extends Enum {

    protected const SPECIAL_ROOM = 16200;
    protected const GENERAL_ROOM = 12960;
}

/**
 * @method static static DORMITORY()
 */
class SpringRoomPriceType extends RoomPriceTypeBase {

    // add spring specific price type
    protected const DORMITORY = 8640;
}


class SummerRoomPriceType extends RoomPriceTypeBase {

    // override parent Enum prices
    protected const SPECIAL_ROOM = 23760;
    protected const GENERAL_ROOM = 21600;
}

/**
 * @method static static DORMITORY()
 */
class AutumnRoomPriceType extends RoomPriceTypeBase {

    // add autumn specific price type
    protected const DORMITORY = 7560;
}

class WinterRoomPriceType extends RoomPriceTypeBase {

    // nothing changes from base room price
}


{
    $priceType = AutumnRoomPriceType::class;

    assert($priceType::has('SPECIAL_ROOM'));
    assert($priceType::has('GENERAL_ROOM'));
    assert($priceType::has('DORMITORY'));


    $priceType = WinterRoomPriceType::class;

    assert($priceType::has('SPECIAL_ROOM'));
    assert($priceType::has('GENERAL_ROOM'));
    assert(!$priceType::has('DORMITORY'));
}

{
    assert(SpringRoomPriceType::of('SPECIAL_ROOM')->value() === 16200);
    assert(SummerRoomPriceType::of('SPECIAL_ROOM')->value() === 23760);

    assert(SpringRoomPriceType::of('DORMITORY')->value() === 8640);
    assert(AutumnRoomPriceType::of('DORMITORY')->value() === 7560);

    assert(SpringRoomPriceType::of('GENERAL_ROOM')->value() === WinterRoomPriceType::of('GENERAL_ROOM')->value());
}