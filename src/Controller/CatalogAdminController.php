<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\CatalogManager;
use App\Model\ItemManager;

/**
 * Class CatalogAdminController
 *
 */
class CatalogAdminController extends AbstractController
{

    /**
     * Display catalogAdmin page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $catalogManager = new CatalogManager();
        $elements = $catalogManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $catalogManager->delete($_POST['id']);
            header('Location: /catalogAdmin/index');
        }

        return $this->twig->render('CatalogAdmin/index.html.twig', ['elements' => $elements]);
    }

    public function delete(int $id)
    {
        $catalogManager = new CatalogManager();
        $catalogManager->delete($id);
    }
}
