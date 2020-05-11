<?php
namespace App\Controller;

class IdentificationController extends AbstractController
{
    /**
     * Display identification page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        return $this->twig->render('Identification/index.html.twig');
    }
}
