<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-06-22
 * Time: 23:54
 */

declare(strict_types=1);

namespace Gachakra\PhpEnum\Examples\Strategies;

require_once __DIR__ . "/../../vendor/autoload.php";

use DomainException;
use Gachakra\PhpEnum\Enum;

/**
 * it could be odd that destinations get weight and create postage with it.
 * just a simple simple example.
 *
 * Interface Destination
 * @package Gachakra\PhpEnum\Examples\Strategies
 */
interface Destination {

    public function postage(Weight $weight): Postage;
}

/**
 * @method static static AFRICA()
 * @method static static ASIA()
 * @method static static EUROPE()
 * @method static static NORTH_AMERICA()
 * @method static static SOUTH_AMERICA()
 * @method static static ANTARCTICA()
 * @method static static AUSTRALIA()
 */
final class AirmailDestination extends Enum implements Destination {

    private const AFRICA = 'Africa';
    private const ASIA = 'Asia';
    private const EUROPE = 'Europe';
    private const NORTH_AMERICA = 'North America';
    private const SOUTH_AMERICA = 'South America';
    private const ANTARCTICA = 'Antarctica';
    private const AUSTRALIA = 'Australia';

    /**
     * @param Weight $weight
     * @return AirmailPostage
     */
    public function postage(Weight $weight): Postage {
        switch ($this) {
            case self::ASIA():
                return LowestAirmailPostage::fromWeight($weight);

            case self::EUROPE():
            case self::NORTH_AMERICA():
            case self::AUSTRALIA():
                return MiddleAirmailPostage::fromWeight($weight);

            case self::AFRICA():
            case self::SOUTH_AMERICA():
                return HighestAirmailPostage::fromWeight($weight);

            default:
                throw new DomainException("Airmail unsupported for $this");
        }
    }
}

/**
 * Class Weight
 * @method static static UP_TO_25_GRAMS()
 * @method static static UP_TO_50_GRAMS()
 */
final class Weight extends Enum {

    private const UP_TO_25_GRAMS = 25;
    private const UP_TO_50_GRAMS = 50;
}

/**
 * Interface Postage (from Japan to overseas)
 */
interface Postage {

    public function rates(): int;

    public function add(Postage $postage): Postage;
}

class AirmailPostage implements Postage {

    /**
     * @var int
     */
    protected $jpy;

    protected function __construct(int $jpy) {
        $this->jpy = $jpy;
    }

    public function rates(): int {
        return $this->jpy;
    }

    public function add(Postage $added): Postage {
        return new self($this->rates() + $added->rates());
    }
}

trait CalculatePostageByWeight {

    public static function fromWeight(Weight $weight): Postage {
        return new static(static::calculateBy($weight));
    }

    protected abstract static function calculateBy(Weight $weight): int;
}

class LowestAirmailPostage extends AirmailPostage {

    use CalculatePostageByWeight;

    protected static function calculateBy(Weight $weight): int {

        switch ($weight) {
            case Weight::UP_TO_25_GRAMS():
                return 90;
            case Weight::UP_TO_50_GRAMS():
                return 160;
            default:
                throw new DomainException();
        }
    }
}

class MiddleAirmailPostage extends AirmailPostage {

    use CalculatePostageByWeight;

    protected static function calculateBy(Weight $weight): int {

        switch ($weight) {
            case Weight::UP_TO_25_GRAMS():
                return 110;
            case Weight::UP_TO_50_GRAMS():
                return 190;
            default:
                throw new DomainException();
        }
    }
}

class HighestAirmailPostage extends AirmailPostage {

    use CalculatePostageByWeight;

    protected static function calculateBy(Weight $weight): int {

        switch ($weight) {
            case Weight::UP_TO_25_GRAMS():
                return 130;
            case Weight::UP_TO_50_GRAMS():
                return 230;
            default:
                throw new DomainException();
        }
    }
}


{
    $postage = AirmailDestination::AFRICA()->postage(Weight::UP_TO_50_GRAMS())
            ->add(AirmailDestination::ASIA()->postage(Weight::UP_TO_25_GRAMS()))
            ->add(AirmailDestination::EUROPE()->postage(Weight::UP_TO_50_GRAMS()));

    assert($postage->rates() === 510);
}