<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerJson
{
    #[Route("/api/quote", name: "quote")]
    public function api(): Response
    {
        $quotes = array(
            1 => "Do you leave your bedroom door open when you sleep? Experts say its better to leave it open.",
            2 => "You might be under feeding your cats because experts say you should be feeding them every hour.",
            3 => "Experts say you should let your cats bite your fingers.",
            4 => "Do you stop your cats from eating flies and bugs? Experts say it good for cats to eat insects."
        );

        $number = random_int(1, 4);

        $data = [
            "quote" => $quotes[$number],
            "the experts" => "my cats",
            "date" => date("Y-m-d"),
            "time" => date("H:i:s")
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;   
    }
}
