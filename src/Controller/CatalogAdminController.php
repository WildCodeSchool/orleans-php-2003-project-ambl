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
use App\Services\UploadeManager;

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

        return $this->twig->render('CatalogAdmin/index.html.twig', ['elements' => $elements]);
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
        $uploadDir = '../public/assets/images/catalog';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataSend = array_map('trim', $_POST);

            /* Verification of form fields */
            $errorsList = $this->checkForm($dataSend);

            /* Checking the field used to upload the file */
            $uploadManager = new UploadeManager($_FILES['picture'], 1000000, $uploadDir);

            if ($_FILES['picture']['error'] == 0) {
                $uploadManager->isValidate();
                $errorsList = array_merge($errorsList, $uploadManager->getErrors());
            } else {
                if ($dataSend['type'] == '1') {
                    $fileName = 'mushroom_image.jpeg';
                } else {
                    $fileName = 'plante_image.jpeg';
                }
            }

            if (empty($errorsList)) {
                if ($_FILES['picture']['error'] == 0) {
                    $fileName = $uploadManager->upload();
                    if ($fileName == '' && $dataSend['type'] == '1') {
                        $fileName = 'mushroom_image.jpeg';
                    } else {
                        $fileName = 'plante_image.jpeg';
                    }
                }

                $catalogManager = new CatalogManager();
                $dataSend['picture'] = $fileName;
                $catalogManager->insert($dataSend);

                header('Location: /catalogAdmin/index');
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
     * Check the conformity of the form fields
     *
     * @param array $dataSend
     * @return array
     */
    private function checkForm(array $dataSend): array
    {
        $errorMessage = [];

        $commonNameLenght = 255;
        if (empty($dataSend['commonName'])) {
            $errorMessage[] = 'Le nom commun doit être renseigné';
        }

        if (strlen($dataSend['commonName']) > $commonNameLenght) {
            $errorMessage[] = 'Le nom commun doit être inférieur a ' . $commonNameLenght . ' caractères';
        }

        $latinNameLenght = 255;
        if (empty($dataSend['latinName'])) {
            $errorMessage[] = 'Le nom latin doit être renseigné';
        }

        if (strlen($dataSend['latinName']) > $latinNameLenght) {
            $errorMessage[] = 'Le nom latin doit être inférieur a ' . $latinNameLenght . ' caractères';
        }

        $colorLenght = 100;
        if (empty($dataSend['color'])) {
            $errorMessage[] = 'Le couleur doit être renseigné';
        }

        if (strlen($dataSend['color']) > $colorLenght) {
            $errorMessage[] = 'Le couleur doit être inférieur a ' . $colorLenght . ' caractères';
        }

        if (empty($dataSend['description'])) {
            $errorMessage[] = 'Le description doit être renseigné';
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
                    $errors[] = 'Le type est inconnue';
                }
            }
        }

        if ($fieldName === 'toxicity') {
            $toxicityManager = new ToxicityManager();
            $toxicities = $toxicityManager->selectAll();

            if (empty($fieldValue) || $fieldValue === '') {
                $errors[] = 'Le toxicity doit être renseigné';
            } else {
                if (!in_array($fieldValue, array_column($toxicities, 'id'))) {
                    $errors[] = 'Le toxicity est inconnue';
                }
            }
        }

        return $errors;
    }
}
