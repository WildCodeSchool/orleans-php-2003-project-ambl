<?php


namespace App\Controller;


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
        return $this->twig->render('Events/index.html.twig');
    }
}