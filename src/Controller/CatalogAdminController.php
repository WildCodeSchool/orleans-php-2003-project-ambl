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
use App\Model\ElementTypeManager;
use App\Model\ToxicityManager;
use App\Services\UploadManager;

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

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $numberPageTotal = 0;
        } else {
            $search = '';
            $numberPageTotal = ceil($catalogManager->getNumberCatalogElement()/$catalogManager::MAX_RESULT);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->delete($_POST['id']);
        }

        $elements = $catalogManager->selectAll($search);
        $numberPage = 1;
        $nextPage = 2;

        return $this->twig->render('CatalogAdmin/index.html.twig', [
            'elements' => $elements,
            'numberPageTotal' => $numberPageTotal,
            'numberPage' => $numberPage,
            'nextPage' => $nextPage,
            'search' => $search
        ]);
    }

    /**
     * Display catalogAdmin creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $elementTypeManager = new ElementTypeManager();
        $toxicityManager = new ToxicityManager();
        $elementTypes = $elementTypeManager->selectAll();
        $toxicities = $toxicityManager->selectAll();
        $errorsList = [];
        $dataSend = [];
        $fileName = '';
        $uploadDir = '../public/uploads/catalog';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataSend = array_map('trim', $_POST);

            /* Verification of form fields */
            $errorsList = $this->checkForm($dataSend);

            /* Checking the field used to upload the file */
            $uploadManager = new UploadManager($_FILES['picture'], 1000000, $uploadDir);

            if ($_FILES['picture']['error'] == 0) {
                $uploadManager->isValidate();
                $errorsList = array_merge($errorsList, $uploadManager->getErrors());
            }

            if (empty($errorsList)) {
                if ($_FILES['picture']['error'] == 0) {
                    $fileName = $uploadManager->upload();
                }

                $catalogManager = new CatalogManager();

                if (empty($dataSend['toxicity'])) {
                    $dataSend['toxicity'] = null;
                }

                $dataSend['picture'] = $fileName;
                $id = $catalogManager->insert($dataSend);

                header('Location: /catalogAdmin/show/' . $id);
            }
        }

        return $this->twig->render('CatalogAdmin/add.html.twig', [
            'elementTypes' => $elementTypes,
            'toxicities' => $toxicities,
            'errors' => $errorsList,
            'dataSend' => $dataSend
        ]);
    }

    /**
     * Display element of catalog informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $catalogManager = new CatalogManager();
        $element = $catalogManager->selectOneById($id);

        return $this->twig->render('CatalogAdmin/show.html.twig', ['element' => $element]);
    }

    /**
     * Edit an element of catalog
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id)
    {
        $elementTypeManager = new ElementTypeManager();
        $toxicityManager = new ToxicityManager();
        $elementTypes = $elementTypeManager->selectAll();
        $toxicities = $toxicityManager->selectAll();
        $catalogManager = new CatalogManager();
        $element = $catalogManager->selectOneById($id);

        $errorsList = [];
        $dataSend = [];
        $fileName = '';
        $uploadDir = '../public/uploads/catalog';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataSend = array_map('trim', $_POST);

            /* Verification of form fields */
            $errorsList = $this->checkForm($dataSend);

            /* Checking the field used to upload the file */
            $uploadManager = new UploadManager($_FILES['picture'], 1000000, $uploadDir);

            if ($_FILES['picture']['error'] == 0) {
                $uploadManager->isValidate();
                $errorsList = array_merge($errorsList, $uploadManager->getErrors());
            }

            if (empty($errorsList)) {
                if ($_FILES['picture']['error'] == 0) {
                    $fileName = $uploadManager->upload();
                }

                $catalogManager = new CatalogManager();

                if (empty($dataSend['toxicity'])) {
                    $dataSend['toxicity'] = null;
                }

                $dataSend['picture'] = $fileName;

                $dataSend['picture'] = $fileName;
                $catalogManager->update($dataSend);

                header('Location: /catalogAdmin/show/' . $id);
            }
        }

        return $this->twig->render('CatalogAdmin/edit.html.twig', [
            'elementTypes' => $elementTypes,
            'toxicities' => $toxicities,
            'errors' => $errorsList,
            'dataSend' => $dataSend,
            'element' => $element
        ]);
    }

    /**
     * Delete an element of catalog
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $catalogManager = new CatalogManager();
        $element = $catalogManager->selectOneById($id);
        if (!empty($element['picture'])) {
            $deletedFile = "../public/uploads/catalog/" . $element['picture'];

            if (unlink($deletedFile)) {
                $catalogManager->delete($id);
            }
        } else {
            $catalogManager->delete($id);
        }

        header('Location: /catalogAdmin/index');
    }

    /**
     * Check the conformity of the form fields
     *
     * @param array $dataSend
     * @return array
     */
    private function checkForm(array $dataSend): array
    {
        $errorMessage = [];

        $commonNameLength = 255;
        if (empty($dataSend['commonName'])) {
            $errorMessage[] = 'Le nom commun doit être renseigné';
        }

        if (strlen($dataSend['commonName']) > $commonNameLength) {
            $errorMessage[] = 'Le nom commun doit être inférieur à ' . $commonNameLength . ' caractères';
        }

        $latinNameLength = 255;
        if (empty($dataSend['latinName'])) {
            $errorMessage[] = 'Le nom latin doit être renseigné';
        }

        if (strlen($dataSend['latinName']) > $latinNameLength) {
            $errorMessage[] = 'Le nom latin doit être inférieur à ' . $latinNameLength . ' caractères';
        }

        $colorLength = 100;
        if (empty($dataSend['color'])) {
            $errorMessage[] = 'La couleur doit être renseignée';
        }

        if (strlen($dataSend['color']) > $colorLength) {
            $errorMessage[] = 'La couleur doit être inférieure à ' . $colorLength . ' caractères';
        }

        if (empty($dataSend['description'])) {
            $errorMessage[] = 'Le description doit être renseignée';
        }

        $errorMessage = array_merge($errorMessage, $this->checkSelectFieldsForm('type', $dataSend['type']));
        $errorMessage = array_merge($errorMessage, $this->checkSelectFieldsForm('toxicity', $dataSend['toxicity']));

        return $errorMessage;
    }

    /**
     * Check the conformity of the selected fields of the form
     *
     * @param string $fieldName
     * @param string $fieldValue
     * @return array
     */
    private function checkSelectFieldsForm(string $fieldName, string $fieldValue): array
    {
        $errors = [];

        if ($fieldName === 'type') {
            $elementTypeManager = new ElementTypeManager();
            $types = $elementTypeManager->selectAll();

            if (empty($fieldValue) || $fieldValue === '') {
                $errors[] = 'Le type doit être renseigné';
            } else {
                if (!in_array($fieldValue, array_column($types, 'id'))) {
                    $errors[] = 'Le type est inconnu';
                }
            }
        }

        if ($fieldName === 'toxicity') {
            $toxicityManager = new ToxicityManager();
            $toxicities = $toxicityManager->selectAll();

            if (!empty($fieldValue) || $fieldValue != '') {
                if (!in_array($fieldValue, array_column($toxicities, 'id'))) {
                    $errors[] = 'La toxicité est inconnue';
                }
            }
        }

        return $errors;
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
    public function page(int $numberPage): string
    {
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            header('Location: /catalog/index/?search=' . $_GET['search']);
        } else {
            $catalogManager = new CatalogManager();
            $numberPageTotal = ceil($catalogManager->getNumberCatalogElement()/$catalogManager::MAX_RESULT);

            if ($numberPage <= 1) {
                $elements = $catalogManager->selectAll();
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

            return $this->twig->render('CatalogAdmin/index.html.twig', [
                'elements' => $elements,
                'numberPageTotal' => $numberPageTotal,
                'numberPage' => $numberPage,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage
            ]);
        }
    }
}
