<?php
namespace App\Controller;

use App\Model\EventManager;

class EventController extends AbstractController
{
    const MAX_TEXT = 255;

    /**
     * Display event page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $eventManager = new EventManager();
        $events = $eventManager->selectTableEvent();

        return $this->twig->render('Event/index.html.twig', ['events' => $events]);
    }
  
    public function admin()
    {
        $eventManager = new EventManager();
        $events = $eventManager->selectTableEvent();

        return $this->twig->render('Event/admin.html.twig', ['events' => $events]);
    }

    public function delete(int $eventId)
    {
        $eventManager = new EventManager();
        $eventManager->delete($eventId);
        
        header('Location: /event/admin');
    }

    public function add()
    {
        $eventManager = new EventManager();
        $types = $eventManager->selectAllType();

        $errors = [];
        $event = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = array_map('trim', $_POST);
            $errors = $this->validateForm($event);

            if (empty($errors)) {
                $eventManager->insert($event);
                header('Location: /event/admin');
            }
        }

        return $this->twig->render('Event/add.html.twig', ['types' => $types,
                                                                  'errors' => $errors,
                                                                  'event' => $event]);
    }

    public function update(int $eventId)
    {
        $eventManager = new EventManager();
        $types = $eventManager->selectAllType();
        $event = $eventManager->selectOneById($eventId);

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = array_map('trim', $_POST);
            $errors = $this->validateForm($event);

            if (empty($errors)) {
                $eventManager->update($event);
                header('Location: /event/admin');
            }
        }

        return $this->twig->render('Event/update.html.twig', ['types' => $types,
            'errors' => $errors,
            'event' => $event]);
    }

    private function validateForm(array $data): array
    {
        $errors = array_merge($this->validateEmpty($data), $this->validateLength($data, self::MAX_TEXT));

        return $errors;
    }

    private function validateLength(array $data, int $length): array
    {
        $errors = [];

        if (strlen($data['title']) > $length) {
            $errors[] = 'Veuillez indiquer un titre inférieur à ' . $length . ' caractères';
        }

        if (strlen($data['location']) > $length) {
            $errors[] = 'Veuillez indiquer un lieu inférieur à ' . $length . ' caractères';
        }

        if (strlen($data['speaker_name']) > $length) {
            $errors[] = 'Veuillez indiquer un nom inférieur à ' . $length . ' caractères';
        }

        return $errors;
    }

    private function validateEmpty(array $data): array
    {
        $errors = [];

        if (empty($data['type_id'])) {
            $errors[] = 'Veuillez choisir un type d\'événement';
        }

        if (empty($data['title'])) {
            $errors[] = 'Veuillez indiquer le titre de l\'événement';
        }

        if (empty($data['date'])) {
            $errors[] = 'Veuillez indiquer la date de l\'événement';
        }

        if (empty($data['hour'])) {
            $errors[] = 'Veuillez indiquer l\'horaire de l\'événement';
        }

        if (empty($data['location'])) {
            $errors[] = 'Veuillez indiquer le lieu de l\'événement';
        }

        return $errors;
    }
}
