<?php
namespace App\Controller;

use App\Model\RequestManager;
use App\Services\UploadManager;

class IdentificationController extends AbstractController
{
    const MAX_VARCHAR = 255;
    const MAX_TEXT = 1000;
    
    /**
     * Display identification page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(int $success = 0)
    {
        $errors = [];
        $request = [];
        $fileName = '';
        $uploadDir = '../public/uploads/identifications';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = array_map('trim', $_POST);

            /* Verification of form fields */
            $errors = $this->validateForm($request);

            /* Checking the field used to upload the file */
            $uploadManager = new UploadManager($_FILES['picture'], 1000000, $uploadDir);

            if ($_FILES['picture']['error'] == 0) {
                $uploadManager->isValidate();
                $errors = array_merge($errors, $uploadManager->getErrors());
            }

            if (empty($errors)) {
                if ($_FILES['picture']['error'] == 0) {
                    $fileName = $uploadManager->upload();
                }

                $requestManager = new RequestManager();
                $request['picture'] = $fileName;
                $requestManager->insert($request);

                header('Location: /identification/index/1');
            }
        }

        return $this->twig->render('Identification/index.html.twig', ['errors' => $errors,
                                                                            'request' => $request,
                                                                            'success' => $success]);
    }

    private function validateForm(array $data): array
    {
        $errors = array_merge($this->validateEmpty($data), $this->validateLength($data));

        if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Le format d\'email est invalide';
        }

        if (empty($_FILES['picture']['name'])) {
            $errors[] = 'Veuillez joindre une photo';
        }

        return $errors;
    }

    private function validateLength(array $data): array
    {
        $errors = [];

        if (strlen($data['sender_name']) > self::MAX_VARCHAR) {
            $errors[] = 'Veuillez indiquer un nom inférieur à ' . self::MAX_VARCHAR . ' caractères';
        }

        if (strlen($data['mail']) > self::MAX_VARCHAR) {
            $errors[] = 'Veuillez indiquer une adresse mail inférieure à ' . self::MAX_VARCHAR . ' caractères';
        }

        if (strlen($data['message']) > self::MAX_TEXT) {
            $errors[] = 'Veuillez écrire un message à ' . self::MAX_TEXT . ' caractères';
        }

        return $errors;
    }

    private function validateEmpty(array $data): array
    {
        $errors = [];

        if (empty($data['sender_name'])) {
            $errors[] = 'Veuillez indiquer votre nom';
        }

        if (empty($data['mail'])) {
            $errors[] = 'Veuillez indiquer votre mail';
        }

        return $errors;
    }
}
