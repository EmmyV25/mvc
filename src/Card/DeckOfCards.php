<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardGraphic;

class DeckOfCards
{
    private $deck = [];
    private $values;
    private $suits;

    public function cards()
    {
        $this->values = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13');
        $this->suits  = array('H', 'D', 'C', 'S');

        // every time it loops through the arrays
        // it creates a new CardGraphic and adds it to the deck
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $cardGraphic = new CardGraphic($value, $suit);

                if ($cardGraphic->get_card_suit() == 'H') {
                    $this->deck[] = $cardGraphic->getHeartsAsString();
                } elseif ($cardGraphic->get_card_suit() == 'D') {
                    $this->deck[] = $cardGraphic->getDiamondsAsString();
                } elseif ($cardGraphic->get_card_suit() == 'C') {
                    $this->deck[] = $cardGraphic->getClubsAsString();
                } elseif ($cardGraphic->get_card_suit() == 'S') {
                    $this->deck[] = $cardGraphic->getSpadesAsString();
                }
            }
        }

        return $this->deck;
    }

    public function shuffled_cards()
    {
        $cards = $this->cards();
        shuffle($cards);

        return $cards;
    }

    public function get_deck()
    {
        return $this->deck;
    }
}
