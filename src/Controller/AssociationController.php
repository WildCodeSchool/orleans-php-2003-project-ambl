<?php


namespace App\Controller;

use App\Model\AssociationManager;
use App\Services\UploadManager;

class AssociationController extends AbstractController
{
    /**
     * Display association page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $associationManager = new AssociationManager();
        $council = $associationManager->selectTableAssociation();

        return $this->twig->render('Association/index.html.twig', ['council' => $council]);
    }
    public function admin()
    {
        $associationManager = new AssociationManager();
        $council = $associationManager->selectTableAssociation();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $associationManager->deleteMember($_POST['id']);
            header('Location: /Association/admin');
        }

        return $this->twig->render('Association/admin.html.twig', ['council' => $council]);
    }

    public function add()
    {
        $errors = [];
        $data = [];
        $fileName = '';
        $uploadDir = '../public/assets/images/council';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);

            /* Verification of form fields */
            $errors = $this->checkAdd($data);

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

                $associationManager = new AssociationManager();
                $data['picture'] = $fileName;
                $associationManager->insertMember($data);

                header('Location: /Association/admin');
            }
        }

        return $this->twig->render('Association/add.html.twig', ['errors' => $errors,
            'data' => $data,]);
    }

    public function edit(int $id)
    {
        $data = [];
        $fileName = '';
        $uploadDir = '../public/assets/images/council';
        $errors = [];

        $associationManager = new AssociationManager();
        $council = $associationManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);

            /* Verification of form fields */
            $errors = $this->checkAdd($data);

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
            }
            $data['picture'] = $fileName;
            $associationManager->editMember($data);

            header('Location: /Association/admin/');
        }

        return $this->twig->render('Association/edit.html.twig', [
            'council' => $council,
            'errors' => $errors,
            'data' => $data
        ]);
    }






    private function checkAdd(array $data): array
    {
        $errors = [];
        if (empty($data['firstname'])) {
            $errors[] = 'Veuillez entrer un prénom';
        }
        if (empty($data['lastname'])) {
            $errors[] = 'Veuillez entrer un nom';
        }
        if (empty($data['role'])) {
            $errors[] = 'Veuillez entrer le rôle du membre au sein du conseil.';
        }
        if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Le format d\'email est invalide.';
        }
        return $errors;
    }
}
