<?php
declare(strict_types=1);

namespace JoshBruce\DiceBag;

use Eightfold\Foldable\Fold;

use Eightfold\Shoop\Shoop;

use JoshBruce\DiceBag\Dn;

class DiceBag extends Fold
{
    private $sides = 6;
    private $count = 1;

    private $rolls = [];

    private $isCast = false;

    static public function roll(int $count = 1, int $sides = 6): DiceBag
    {
        $bag = static::fold($sides, $count);
        $bag->result();
        return $bag;
    }

    /**
     * roll7d6 -> roll(7, 6)
     */
    static public function __callStatic(string $name, array $args = []): DiceBag
    {
        $name = Shoop::this($name);
        $numbers = $name->divide("d", false, 2);
        $sides   = intval($numbers->last()->unfold());
        $count   = intval($numbers->first()->last()->unfold());
        return static::roll($count, $sides);
    }

    public function __construct(int $sides = 6, int $count = 1)
    {
        $this->sides = $sides;
        $this->count = $count;
    }

    public function isCast(): bool
    {
        return $this->isCast;
    }

    public function rolls(): array
    {
        if (count($this->rolls) === 0) {
            $this->rolls = Shoop::this(range(1, $this->count))
                ->each(fn($d) => Dn::withSides($this->sides))->unfold();
            $this->isCast = true;
        }
        return $this->rolls;
    }

    public function result(): array
    {
        return $this->rolls();
    }

    public function sort(bool $highToLow = true): DiceBag
    {
        usort($this->rolls(), function($a, $b) use ($highToLow) {
            return ($highToLow)
                ? $a->roll() < $b->roll()
                : $a->roll() > $b->roll();
        });
        return $this;
    }

    public function countHigherThan(int $value): int
    {
        $found = 0;
        foreach ($this->rolls() as $die) {
            if ($die->roll() > $value) {
                $found += 1;
            }
        }
        return $found;
    }

    public function countHigherThanOrEqualTo(int $value): int
    {
        return $this->countHigherThan($value) + $this->countEqualTo($value);
    }

    public function countLowerThan(int $value): int
    {
        $found = 0;
        foreach ($this->rolls() as $die) {
            if ($die->roll() < $value) {
                $found += 1;
            }
        }
        return $found;
    }

    public function countLowerThanOrEqualTo(int $value): int
    {
        return $this->countLowerThan($value) + $this->countEqualTo($value);
    }

    public function countEqualTo(int $value): int
    {
        $found = 0;
        foreach ($this->rolls() as $die) {
            if ($die->roll() === $value) {
                $found += 1;
            }
        }
        return $found;
    }

    public function hasEqualTo(int $value): bool
    {
        return $this->countEqualTo($value) > 0;
    }

    public function highest(int $length = 1): array
    {
        $this->sort();
        $result = array_slice($this->rolls(), 0, $length);
        return Shoop::this($result)->each(fn($d) => $d->roll())->unfold();
    }

    public function lowest(int $length = 1): array
    {
        $this->sort(false);
        $result = array_slice($this->rolls(), 0, $length);
        return Shoop::this($result)->each(fn($d) => $d->roll())->unfold();
    }

    public function sum(): int
    {
        $rolls = Shoop::this($this->rolls())->each(fn($d) => $d->roll())
            ->unfold();
        return array_sum($rolls);
    }

    public function __toString()
    {
        return "rolls: ". implode(", ", $this->rolls());
    }

    public function __debugInfo()
    {
        return [
            "pool"   => $this->count,
            "sides"  => $this->sides,
            "isCast" => $this->isCast(),
            "rolls"  => $this->rolls()
        ];
    }
}
