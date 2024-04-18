<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionController extends AbstractController
{
    #[Route("/session", name: "session", methods: ['GET'])]
    public function session(
        SessionInterface $session
    ): Response
    {
        $data = [
            'session' => $session->all()
        ];

        return $this->render('card/session.html.twig', $data);
    }

    #[Route("/session/delete", name: "clear_session", methods: ['GET'])]
    public function clearSession(
        SessionInterface $session
    ): Response
    {
        $session->clear();

        $this->addFlash(
            'notice',
            'Session has been deleted!'
        );

        return $this->redirectToRoute('session');
    }
}
