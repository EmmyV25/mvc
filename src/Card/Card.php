<?php

namespace App\Card;

class Card
{
    protected $value; // ace, 2, 3, 4, 5, 6, 7, 8, 9, 10, jack, queen, king
    protected $suit; // hearts, diamonds, clubs, spades

    public function __construct($value = null, $suit = null)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    public function get_card_value()
    {
        return $this->value;
    }

    public function get_card_suit()
    {
        return $this->suit;
    }
}
