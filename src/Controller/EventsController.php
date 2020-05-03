<?php
namespace App\Controller;

use App\Model\EventsManager;

class EventsController extends AbstractController
{
    /**
     * Display events page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $eventsManager = new EventsManager();
        $events = $eventsManager->selectTableEvents();

        return $this->twig->render('Events/index.html.twig', ['events' => $events]);
    }

    public function index()
    {
        $eventsManager = new EventsManager();
        $events = $eventsManager->selectTableEvents();

        return $this->twig->render('Events/admin.html.twig', ['events' => $events]);
    }
}
