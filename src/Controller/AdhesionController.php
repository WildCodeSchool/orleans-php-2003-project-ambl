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
        $contactSuccess = $_GET['status'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactData = array_map('trim', $_POST);
            $contactErrors = $this->checkDataForm($contactData);
            if (!empty($contactErrors)) {
                return $this->twig->render(
                    'Adhesion/index.html.twig',
                    ['contactErrors' => $contactErrors, 'contactData' => $contactData]
                );
            } else {
                header('Location: /Adhesion/index/?status=success');
            }
        }   return $this->twig->render('Adhesion/index.html.twig', ['contactSuccess' => $contactSuccess]);
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
        if (empty($data['adhesion_firstname'])) {
            $contactErrors[] = 'Un prénom est requis.';
        }
        if (empty($data['adhesion_lastname'])) {
            $contactErrors[] = 'Un nom est requis.';
        }
        if (!filter_var($data['adhesion_email'], FILTER_VALIDATE_EMAIL)
            || (empty($data['adhesion_email']))) {
            $contactErrors[] = 'Le format d\'email est invalide.';
        }
        if (empty($data['adhesion_phonenumber'])) {
            $contactErrors[] = 'Un numéro de téléphone est requis.';
        }
        if (!is_numeric($data['adhesion_phonenumber'])) {
            $contactErrors[] = 'Le numéro de téléphone doit être composé de chiffres uniquement.';
        }
        if (strlen($data['adhesion_phonenumber']) !== 10) {
            $contactErrors[] = 'Le numéro de téléphone doit être composé de dix chiffres.';
        }
        if (empty($data['adhesion_adress'])) {
            $contactErrors[] = 'Votre adresse ne peut être vide.';
        }
        return $contactErrors;
    }
}
