<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function __construct($value = null, $suit = null)
    {
        parent::__construct($value, $suit);
    }

    private $heartsGraphic = [
        "🂱", "🂲", "🂳", "🂴", "🂵", "🂶", "🂷", "🂸", "🂹", "🂺", "🂻", "🂽", "🂾"
    ];

    private $diamondsGraphic = [
        "🃁", "🃂", "🃃", "🃄", "🃅", "🃆", "🃇", "🃈", "🃉", "🃊", "🃋", "🃍", "🃎"
    ];

    private $clubsGraphic = [
        "🃑", "🃒", "🃓", "🃔", "🃕", "🃖", "🃗", "🃘", "🃙", "🃚", "🃛", "🃝", "🃞"
    ];

    private $spadesGraphic = [
        "🂡", "🂢", "🂣", "🂤", "🂥", "🂦", "🂧", "🂨", "🂩", "🂪", "🂫", "🂭", "🂮"
    ];

    public function redCards()
    {
        $redCards = [];

        $num = count($this->heartsGraphic) + count($this->diamondsGraphic);

        for ($x = 0; $x < $num; $x++) {
            array_push($redCards, array_pop($this->heartsGraphic));
            array_push($redCards, array_pop($this->diamondsGraphic));
        }

        return $redCards;
    }

    public function blackCards()
    {
        $blackCards = [];

        $num = count($this->clubsGraphic) + count($this->spadesGraphic);

        for ($x = 0; $x < $num; $x++) {
            array_push($blackCards, array_pop($this->clubsGraphic));
            array_push($blackCards, array_pop($this->spadesGraphic));
        }

        return $blackCards;
    }

    public function getHeartsAsString(): string
    {
        return $this->heartsGraphic[$this->value - 1];
    }

    public function getDiamondsAsString(): string
    {
        return $this->diamondsGraphic[$this->value - 1];
    }

    public function getClubsAsString(): string
    {
        return $this->clubsGraphic[$this->value - 1];
    }

    public function getSpadesAsString(): string
    {
        return $this->spadesGraphic[$this->value - 1];
    }
}
