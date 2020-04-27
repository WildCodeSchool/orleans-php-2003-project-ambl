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
        if (isset($_GET['status'])) {
            $contactSuccess = $_GET['status'] ?? '';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactData = array_map('trim', $_POST);
            $contactErrors = $this->checkDataForm($contactData);
            if (!empty($contactErrors)) {
                return $this->twig->render(
                    'Contact/index.html.twig',
                    ['contactErrors' => $contactErrors, 'contactData' => $contactData]
                );
            } else {
                header('Location: /Contact/index/?status=success');
            }
        }
        return $this->twig->render('Contact/index.html.twig', ['contactSuccess' => $contactSuccess]);
    }

    /**
     * Method required to check form
     * @return array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function checkDataForm(array $data): array
    {
        $contactErrors = [];
        if (empty($data['contact_firstname'])) {
            $contactErrors[] = 'Un prénom est requis.';
        }
        if (empty($data['contact_lastname'])) {
            $contactErrors[] = 'Un nom est requis.';
        }
        if (!filter_var($data['contact_email'], FILTER_VALIDATE_EMAIL)
            || (empty($data['contact_email']))) {
            $contactErrors[] = 'Le format d\'email est invalide.';
        }
        if (empty($data['contact_phonenumber'])) {
            $contactErrors[] = 'Un numéro de téléphone est requis.';
        }
        if (!is_numeric($data['contact_phonenumber'])) {
            $contactErrors[] = 'Le numéro de téléphone doit être composé de chiffres uniquement.';
        }
        if (strlen($data['contact_phonenumber']) !== 10) {
            $contactErrors[] = 'Le numéro de téléphone doit être composé de dix chiffres.';
        }
        if (empty($data['contact_message'])) {
            $contactErrors[] = 'Votre message ne peut être vide.';
        }
        return $contactErrors;
    }
}
