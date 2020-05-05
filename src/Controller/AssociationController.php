<?php


namespace App\Controller;

use App\Model\AssociationManager;

class AssociationController extends AbstractController
{
    /**
     * Display association page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $associationManager = new AssociationManager();
        $council = $associationManager->selectTableAssociation();

        return $this->twig->render('Association/index.html.twig', ['council' => $council]);
    }
}
