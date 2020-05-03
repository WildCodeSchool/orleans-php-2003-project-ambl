<?php
namespace App\Controller;

use App\Model\EventsManager;

class EventsController extends AbstractController
{
    const MAX_TEXT = 255;

    /**
     * Display events page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $eventsManager = new EventsManager();
        $events = $eventsManager->selectTableEvents();

        return $this->twig->render('Events/index.html.twig', ['events' => $events]);
    }

    public function add()
    {
        $eventsManager = new EventsManager();
        $types = $eventsManager->selectAllType();

        $errors = [];
        $event = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = array_map('trim', $_POST);
            $errors = $this->validateForm($event);

            if (empty($errors)) {
                $eventsManager->insert($event);
                header('Location: /events/admin');
            }
        }

        return $this->twig->render('Events/add.html.twig', ['types' => $types,
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
            $errors[] = 'Veuillez indiquer un titre inférieur à ' . self::MAX_TEXT . ' caractères';
        }

        if (strlen($data['location']) > $length) {
            $errors[] = 'Veuillez indiquer un lieu inférieur à ' . self::MAX_TEXT . ' caractères';
        }

        if (strlen($data['speaker_name']) > $length) {
            $errors[] = 'Veuillez indiquer un nom inférieur à ' . self::MAX_TEXT . ' caractères';
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
