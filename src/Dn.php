<?php

namespace JoshBruce\DiceBag;

use Eightfold\Foldable\Fold;

class Dn extends Fold
{
    private $roll;

    static public function withSides(int $sides = 6)
    {
        return static::fold($sides);
    }

    public function __construct(int $sides = 6)
    {
        $this->roll = rand(1, $sides);
    }

    public function roll()
    {
        return $this->roll;
    }

    public function __debugInfo()
    {
        return [
            "roll" => $this->roll
        ];
    }

    public function __toString()
    {
        return "{$this->roll}";
    }
}
