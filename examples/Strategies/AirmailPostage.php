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
use OutOfRangeException;


/**
 * Usage
 */
{
    $postage = AirmailDestination::AFRICA()->postage(Airmail::fromGrams(48))
            ->add(AirmailDestination::ASIA()->postage(Airmail::fromGrams(24)))
            ->add(AirmailDestination::EUROPE()->postage(Airmail::fromGrams(32)));

    assert($postage->rates() === 510);
}


/**
 * it could be odd that destinations get mail and create postage with it.
 * just a simple simple example.
 *
 * Interface Destination
 * @package Gachakra\PhpEnum\Examples\Strategies
 */
interface Destination {

    public function postage(Mail $mail): Postage;
}

interface WeightType {

    public static function fromGrams(int $grams): WeightType;
}

interface Mail {

    public function weightType(): WeightType;
}

/**
 * Interface Postage (from Japan to overseas)
 */
interface Postage {

    public function rates(): int;

    public function add(Postage $postage): Postage;
}

/**
 * from Japan
 *
 * @method static static AFRICA()
 * @method static static ASIA()
 * @method static static EUROPE()
 * @method static static NORTH_AMERICA()
 * @method static static SOUTH_AMERICA()
 * @method static static ANTARCTICA()
 * @method static static AUSTRALIA()
 */
final class AirmailDestination extends Enum implements Destination {

    private const AFRICA        = 'Africa';
    private const ASIA          = 'Asia';
    private const EUROPE        = 'Europe';
    private const NORTH_AMERICA = 'North America';
    private const SOUTH_AMERICA = 'South America';
    private const ANTARCTICA    = 'Antarctica';
    private const AUSTRALIA     = 'Australia';

    /**
     * or you can use dispatch table instead of switch
     *
     * @param Mail $mail
     * @return AirmailPostage
     */
    public function postage(Mail $mail): Postage {
        switch ($this) {
            case self::ASIA():
                return LowestAirmailPostage::of($mail);

            case self::EUROPE():
            case self::NORTH_AMERICA():
            case self::AUSTRALIA():
                return MiddleAirmailPostage::of($mail);

            case self::AFRICA():
            case self::SOUTH_AMERICA():
                return HighestAirmailPostage::of($mail);

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
final class AirmailWeightType extends Enum implements WeightType {

    private const UP_TO_25_GRAMS = 25;
    private const UP_TO_50_GRAMS = 50;

    public static function fromGrams(int $grams): WeightType {
        if ($grams <= self::UP_TO_25_GRAMS) {
            return self::UP_TO_25_GRAMS();
        }
        if ($grams <= self::UP_TO_50_GRAMS) {
            return self::UP_TO_50_GRAMS();
        }
        throw new OutOfRangeException();
    }
}


class Airmail implements Mail {

    /**
     * @var int
     */
    private $grams;
    /**
     * @var WeightType
     */
    private $weightType;

    public static function fromGrams(int $grams): self {
        return new self($grams, AirmailWeightType::fromGrams($grams));
    }

    private function __construct(int $grams, WeightType $weightType) {
        $this->grams = $grams;
        $this->weightType = $weightType;
    }

    public function weightType(): WeightType {
        return $this->weightType;
    }
}


class AirmailPostage implements Postage {

    /**
     * @var int
     */
    protected $jpy;

    protected function __construct(int $jpy) {
        if ($jpy < 0) {
            throw new OutOfRangeException(); // TODO message
        }
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

    public static function of(Mail $mail): Postage {
        return new static(static::calculateBy($mail->weightType()));
    }

    protected abstract static function calculateBy(WeightType $weight): int;
}


class LowestAirmailPostage extends AirmailPostage {

    use CalculatePostageByWeight;

    protected static function calculateBy(AirmailWeightType $weight): int {

        switch ($weight) {
            case AirmailWeightType::UP_TO_25_GRAMS():
                return 90;
            case AirmailWeightType::UP_TO_50_GRAMS():
                return 160;
            default:
                throw new DomainException();
        }
    }
}


class MiddleAirmailPostage extends AirmailPostage {

    use CalculatePostageByWeight;

    protected static function calculateBy(AirmailWeightType $weight): int {

        switch ($weight) {
            case AirmailWeightType::UP_TO_25_GRAMS():
                return 110;
            case AirmailWeightType::UP_TO_50_GRAMS():
                return 190;
            default:
                throw new DomainException();
        }
    }
}


class HighestAirmailPostage extends AirmailPostage {

    use CalculatePostageByWeight;

    protected static function calculateBy(AirmailWeightType $weight): int {

        switch ($weight) {
            case AirmailWeightType::UP_TO_25_GRAMS():
                return 130;
            case AirmailWeightType::UP_TO_50_GRAMS():
                return 230;
            default:
                throw new DomainException();
        }
    }
}