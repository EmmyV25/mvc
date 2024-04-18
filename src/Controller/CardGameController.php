<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function card(
        SessionInterface $session
    ): Response
    {
        return $this->render('card/card.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function deck(
        SessionInterface $session
    ): Response
    {
        $deck = new DeckOfCards();
        $graphic = new CardGraphic();

        $session->set("deck_in_order", $deck->cards());

        $data = [
            "cards" => $session->get("deck_in_order"),
            "redCards" => $graphic->redCards(),
            "blackCards" => $graphic->blackCards()
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "shuffle_cards")]
    public function shuffle(
        SessionInterface $session
    ): Response
    {
        $deck = new DeckOfCards();
        $graphic = new CardGraphic();
        
        $session->set("shuffled_deck", $deck->shuffled_cards());
        $session->remove('available');

        $data = [
            "shuffledDeck" => $session->get("shuffled_deck"),
            "redCards" => $graphic->redCards(),
            "blackCards" => $graphic->blackCards()
        ];

        return $this->render('card/shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "draw_card")]
    public function draw(
        SessionInterface $session
    ): Response
    {
        $graphic = new CardGraphic();
        $available = $session->get("available");
    
        if ($available) {
           
            $shuffledDeck = $session->get("current_deck");
        } 
        else {
            $deck = new DeckOfCards();
            $shuffledDeck = $deck->shuffled_cards();
        }

        $card = array_pop($shuffledDeck);
        $cardsLeft = count($shuffledDeck);

        if ($cardsLeft == 0) {
            $this->addFlash(
                'warning',
                'No card left! Clear session or go to card/deck/shuffle to reset!'
            );

            $session->set("available", "no");
        }
        else {
            $session->set("available", "yes");
        }

        $session->set("current_deck", $shuffledDeck);
        $session->set("card", $card);

        $data = [
            "current_deck" => $session->get("current_deck"),
            "card" => $session->get("card"),
            "cards_left" => $cardsLeft,
            "redCards" => $graphic->redCards(),
            "blackCards" => $graphic->blackCards()
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "draw_cards")]
    public function drawCards(
        int $num,
        SessionInterface $session
    ): Response
    {
        $graphic = new CardGraphic();
        $available = $session->get("available");
        
        if ($available) {
           
            $shuffledDeck = $session->get("current_deck");
        } else {
            $deck = new DeckOfCards();
            $shuffledDeck = $deck->shuffled_cards();
        }

        $cards = [];
        $cardsLeft = count($shuffledDeck);

        if ($num <= $cardsLeft) {

            for ($x = 0; $x < $num; $x++) {
                array_push($cards, array_pop($shuffledDeck));
            }

        } elseif ($num > $cardsLeft && $cardsLeft != 0) {
            $this->addFlash(
                'warning',
                'Too few cards!'
            );
        }

        if (count($shuffledDeck) == 0) {
            $this->addFlash(
                'warning',
                'No card left! Clear session or go to card/deck/shuffle to reset!'
            );

            $session->set("available", "no");
        } else {
            $session->set("available", "yes");
        }

        $session->set("current_deck", $shuffledDeck);

        $data = [
            "cards" => $cards,
            "num" => $num,
            "cards_left" => count($shuffledDeck),
            "redCards" => $graphic->redCards(),
            "blackCards" => $graphic->blackCards()
        ];

        return $this->render('card/draw_cards.html.twig', $data);
    }

    #[Route("/card/deck/draw_post", name: "draw_cards_post", methods: ['POST'])]
    public function drawCardsPost(
        Request $request
    ): Response {
        $numCards = $request->request->get('num_cards');

        return $this->redirectToRoute('draw_cards', ["num" => $numCards]);
    }

}
