<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\CatalogManager;

class CatalogController extends AbstractController
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
        $catalogManager = new CatalogManager();

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $numberPageTotal = 0;
        } else {
            $search = '';
            $numberPageTotal = ceil($catalogManager->getNumberCatalogElement()/$catalogManager::MAX_RESULT);
        }

        $elements = $catalogManager->selectAll($search);
        $numberPage = 1;
        $nextPage = 2;

        return $this->twig->render('Catalog/index.html.twig', [
            'elements' => $elements,
            'numberPageTotal' => $numberPageTotal,
            'numberPage' => $numberPage,
            'nextPage' => $nextPage
        ]);
    }

    /**
     * Manage navigation from one page to another
     *
     * @param int $numberPage
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function page($numberPage)
    {
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            header('Location: /catalog/index/?search=' . $_GET['search']);
        } else {
            $catalogManager = new CatalogManager();
            $numberPageTotal = ceil($catalogManager->getNumberCatalogElement()/$catalogManager::MAX_RESULT);

            if ($numberPage <= 1) {
                $elements = $catalogManager->selectAll('');
                $numberPage = 1;
                $previousPage = 0;
                $nextPage = 2;
            } elseif ($numberPage > $numberPageTotal) {
                $numberPage = $numberPageTotal;
                $elements = $catalogManager->selectByPage($numberPage);
                $previousPage = $numberPage - 1;
                $nextPage = $numberPage + 1;
            } else {
                $elements = $catalogManager->selectByPage($numberPage);
                $previousPage = $numberPage - 1;
                $nextPage = $numberPage + 1;
            }

            return $this->twig->render('Catalog/index.html.twig', [
                'elements' => $elements,
                'numberPageTotal' => $numberPageTotal,
                'numberPage' => $numberPage,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage
            ]);
        }
    }
}
