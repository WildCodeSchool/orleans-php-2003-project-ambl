<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

class ContactController extends AbstractController
{

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
        return $this->twig->render('Contact/contact.html.twig');
    }

    public function send()
    {

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $contactData['contact_firstname'] = trim($_POST['contact_firstname']);
            $contactData['contact_lastname'] = trim($_POST['contact_lastname']);
            $contactData['contact_email'] = trim($_POST['contact_email']);
            $contactData['contact_phonenumber'] = trim($_POST['contact_phonenumber']);

            $contactErrors = [];
            if (empty($_POST['contact_firstname'])) {
                $contactErrors[] = 'Un prénom est requis';
            }
            if (empty($_POST['contact_lastname'])) {
                $contactErrors[] = 'Un nom est requis';
            }
            if (empty($_POST['contact_email'])) {
                $contactErrors[] = 'Un email est requis';
            }
            if (!filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) {
                $contactErrors[] = 'Le format d\'email est invalide';
            }
            if (empty($_POST['contact_phonenumber'])) {
                $contactErrors[] = 'Un numéro de téléphone est requis';
            }
            if (empty($_POST['contact_message'])) {
                $contactErrors[] = 'Votre message ne peut être  vide';
            }
        }
    }
}
