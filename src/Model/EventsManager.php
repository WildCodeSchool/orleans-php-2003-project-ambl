<?php
namespace App\Model;

class EventsManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'event';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Selects all the relevant informations from the tables related to the events.
     *
     * @return array
     */
    public function selectTableEvents(): array
    {
        $query = "SELECT t.type, e.title, e.date, e.location, e.hour, CONCAT(s.firstname, ' ', s.lastname) AS fullname
                  FROM event e 
                  JOIN event_type t ON e.type_id = t.id
                  LEFT JOIN speaker s ON e.speaker_id = s.id
                  ORDER BY e.date, e.hour";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectNextEvents(int $nbEvents): array
    {
        $query = "SELECT e.title, e.date, t.image
                  FROM event e
                  JOIN event_type t ON e.type_id = t.id
                  ORDER BY e.date, e.hour
                  LIMIT " . $nbEvents;
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
