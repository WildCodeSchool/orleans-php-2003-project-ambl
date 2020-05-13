<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\BookManager;
use App\Model\LinkManager;

class BookController extends AbstractController
{

    /**
     * Display catalog page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $bookManager = new BookManager();
        $books = $bookManager->selectAll();

        $linkManager = new LinkManager();
        $links = $linkManager->selectAll();

        return $this->twig->render('Ressources/index.html.twig', ['books' => $books, 'links' => $links]);
    }
}
