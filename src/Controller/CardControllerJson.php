<?php

namespace App\Controller;

use App\Card\DeckOfCards;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerJson extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "api_deck" => $deck->cards()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle", methods: ['POST'])]
    public function apiShuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();
        $session->set("api_shuffled_deck", $deck->shuffled_cards());
        $session->remove('available');

        $data = [
            "api_shuffled_deck" => $deck->shuffled_cards()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/draw", name: "api_draw_card", methods: ['POST'])]
    public function apiDrawCard(
        SessionInterface $session
    ): Response {
        $available = $session->get("available");

        if ($available) {
            $shuffledDeck = $session->get("current_deck");
        } else {
            $deck = new DeckOfCards();
            $shuffledDeck = $deck->shuffled_cards();
        }

        $card = array_pop($shuffledDeck);
        $cardsLeft = count($shuffledDeck);

        $session->set("current_deck", $shuffledDeck);
        $session->set("available", "yes");
        $session->set("card", $card);

        $data = [
            "card" => $session->get("card"),
            "cards_left" => $cardsLeft
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "api_draw_cards")]
    public function apiDrawCards(
        int $num,
        SessionInterface $session
    ): Response {
        $available = $session->get("available");

        if ($available) {
            $shuffledDeck = $session->get("current_deck");
        } else {
            $deck = new DeckOfCards();
            $shuffledDeck = $deck->shuffled_cards();
        }

        $cards = [];

        if ($num <= count($shuffledDeck)) {
            for ($x = 0; $x < $num; $x++) {
                array_push($cards, array_pop($shuffledDeck));
            }
        }

        $session->set("current_deck", $shuffledDeck);
        $session->set("available", "yes");

        $data = [
            "num" => $num,
            "cards" => $cards,
            "cards_left" => count($shuffledDeck)
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/draw_post", name: "api_draw_cards_post", methods: ['POST'])]
    public function apiDrawCardsPost(
        Request $request
    ): Response {
        $numCards = $request->request->get('num_cards');

        return $this->redirectToRoute('api_draw_cards', ["num" => $numCards]);
    }
}
