# Dice Bag

A PHP library for "rolling" *n* dice, with *n* sides each.

## Installation

```
composer require joshbruce/php-dice-bag
```

## Usage

Say you are rolling a character for an RPG using a fairly standard method (4d6, drop the lowest).

```php
DiceBag::roll4d6()->highest(3)->sum();
// step 1: [3, 5, 2, 1]
// step 2: [5, 3, 2]
// step 3: 10
```

You do not need to use the magic static method:

```php
DiceBag::roll(4, 6)->highest(3)->sum();
// step 1: [3, 5, 2, 1]
// step 2: [5, 3, 2]
// step 3: 10
```

You can also roll a single die using one of the following:

```php
DiceBag::roll();
// rolls 1d6

DiceBag::roll(1, 6);
// rolls 1d6

Dn::withSides();
// roll 1 die with the given number of sides
```

The values of the rolls are calculated at instantiation. Therefore, the various fluent methods are about manipulating the initial results or retrieving values from them.

## Details

Effectively this is a random number generator duplicating the feel and spread of physical dice. The minimum value is always 1.

Inspired by [AnyDice](https://anydice.com) and driven by the desire to create an [MORPG](https://en.wikipedia.org/wiki/Massively_multiplayer_online_role-playing_game) using various dice-based systems; specifically [7DSystem](http://www.7dsystem.com) and the [Cypher System](http://cypher-system.com).

## Other

See the .github directory for various details including code of conduct, constribution notes, and so on.
