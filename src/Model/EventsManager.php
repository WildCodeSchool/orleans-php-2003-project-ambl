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
        $query = "SELECT t.type, e.title, e.date, e.location, e.hour, e.speaker_name, e.id
                  FROM event e 
                  JOIN event_type t ON e.type_id = t.id
                  ORDER BY e.date, e.hour";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectNextEvents(int $nbEvents): array
    {
        $query = "SELECT e.title, e.date, t.image, t.type
                  FROM event e
                  JOIN event_type t ON e.type_id = t.id
                  ORDER BY e.date, e.hour
                  LIMIT " . $nbEvents;
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectAllType()
    {
        $query = "SELECT *
                  FROM event_type";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert(array $item)
    {
        $query = "INSERT INTO event (title, date, hour, location, speaker_name, type_id)
                  VALUES (:title, :date, :hour, :location, :speaker_name, :type_id)";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue('title', $item['title'], \PDO::PARAM_STR);
        $statement->bindValue('date', $item['date'], \PDO::PARAM_STR);
        $statement->bindValue('hour', $item['hour'], \PDO::PARAM_STR);
        $statement->bindValue('location', $item['location'], \PDO::PARAM_STR);
        $statement->bindValue('speaker_name', $item['speaker_name'], \PDO::PARAM_STR);
        $statement->bindValue('type_id', $item['type_id'], \PDO::PARAM_INT);

        $statement->execute();
    }
}
