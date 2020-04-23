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
    public function index(string $contactSuccess = '')
    {
        if ($contactSuccess == 'success') {
            $message = "Votre message a bien été envoyé. Merci.";
        } else {
            $message = '';
        }
        return $this->twig->render('Contact/index.html.twig', ['contactSuccess' => $message]);
    }

    /**
     * Method required to check form
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function checkingForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactData = array_map('trim', $_POST);
            $contactData['contact_firstname'] = ucfirst($contactData['contact_firstname']);
            $contactData['contact_lastname'] = ucfirst($contactData['contact_lastname']);

            $contactErrors = [];
            $contactSuccess = '';
            if (empty($contactData['contact_firstname'])) {
                $contactErrors[] = 'Un prénom est requis.';
            }
            if (empty($contactData['contact_lastname'])) {
                $contactErrors[] = 'Un nom est requis.';
            }
            if (empty($contactData['contact_email'])) {
                $contactErrors[] = 'Un email est requis.';
            }
            if (!filter_var($contactData['contact_email'], FILTER_VALIDATE_EMAIL)) {
                $contactErrors[] = 'Le format d\'email est invalide.';
            }
            if (empty($contactData['contact_phonenumber'])) {
                $contactErrors[] = 'Un numéro de téléphone est requis.';
            }
            if (!is_numeric($contactData['contact_phonenumber'])) {
                $contactErrors[] = 'Le numéro de téléphone doit être composé de chiffres uniquement.';
            }
            if (strlen($contactData['contact_phonenumber']) > 10) {
                $contactErrors[] = 'Le numéro de téléphone doit être composé de dix chiffres seulement.';
            }
            if (strlen($contactData['contact_phonenumber']) < 10) {
                $contactErrors[] = 'Le numéro de téléphone doit être composé de 10 chiffres.';
            }
            if (empty($contactData['contact_message'])) {
                $contactErrors[] = 'Votre message ne peut être vide.';
            }
            if (!empty($contactErrors)) {
                return $this->twig->render('/Contact/index.html.twig', ['contactErrors' => $contactErrors]);
            }
            if (empty($contactErrors)) {
                $contactSuccess = "success";
                header('Location: /Contact/index/' . $contactSuccess);
            }
        }
    }
}
