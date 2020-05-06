<?php
namespace App\Model;

class EventManager extends AbstractManager
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
    public function selectTableEvent(): array
    {
        $query = "SELECT t.type, t.image, e.title, e.date, e.location, e.hour, e.speaker_name, e.id
                  FROM event e 
                  JOIN event_type t ON e.type_id = t.id
                  ORDER BY e.date, e.hour";
        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectNextEvents(int $nbEvents): array
    {
        $query = "SELECT e.title, e.date, e.location, e.speaker_name, t.image, t.type
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

    public function update(array $item):bool
    {
        $query = "UPDATE event
                  SET title = :title, date = :date, hour = :hour, location = :location, speaker_name = :speaker_name,
                  type_id = :type_id
                  WHERE id = :id";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue('title', $item['title'], \PDO::PARAM_STR);
        $statement->bindValue('date', $item['date'], \PDO::PARAM_STR);
        $statement->bindValue('hour', $item['hour'], \PDO::PARAM_STR);
        $statement->bindValue('location', $item['location'], \PDO::PARAM_STR);
        $statement->bindValue('speaker_name', $item['speaker_name'], \PDO::PARAM_STR);
        $statement->bindValue('type_id', $item['type_id'], \PDO::PARAM_INT);
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function delete(int $id)
    {
        $query = "DELETE FROM event
                  WHERE id = :id";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue('id', $id, \PDO::PARAM_INT);

        $statement->execute();
    }
}
