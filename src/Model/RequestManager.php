<?php
namespace App\Model;

class RequestManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'request';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $request)
    {
        $query = "INSERT INTO" . self::TABLE . " (sender_name, mail, message, picture)
                  VALUES (:sender_name, :mail, :message, :picture)";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue('sender_name', $request['title'], \PDO::PARAM_STR);
        $statement->bindValue('mail', $request['mail'], \PDO::PARAM_STR);
        $statement->bindValue('message', $request['message'], \PDO::PARAM_STR);
        $statement->bindValue('picture', $request['picture'], \PDO::PARAM_STR);

        $statement->execute();
    }
}
