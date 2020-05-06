<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\CatalogManager;
use App\Model\EventManager;

class HomeController extends AbstractController
{
    const NB_EVENTS = 3;

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $eventManager = new EventManager();
        $nextEvents = $eventManager->selectNextEvents(self::NB_EVENTS);

        $catalogManager = new CatalogManager();
        $randomVegetal = $catalogManager->selectOneAtRandom();

        return $this->twig->render('Home/index.html.twig', ["nextEvents" => $nextEvents,
                                                                  "randomVegetal" => $randomVegetal]);
    }
}
