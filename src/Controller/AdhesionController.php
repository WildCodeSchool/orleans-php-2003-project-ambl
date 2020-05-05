<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

class AdhesionController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Adhesion/index.html.twig');
    }
}
