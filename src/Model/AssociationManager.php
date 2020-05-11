<?php

namespace App\Model;

class AssociationManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'council';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Selects all the relevant informations from the tables related to the association.
     *
     * @return array
     */




    public function selectTableAssociation(): array
    {
        $query ='SELECT * FROM council ORDER BY firstname';
        return $this->pdo->query($query)->fetchAll();
    }

    public function insertMember(array $data)
    {
        $query="INSERT INTO council (firstname, lastname, role, mail, picture) 
        VALUES (:firstname, :lastname, :role, :mail, :picture)";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue('firstname', $data['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $data['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('role', $data['role'], \PDO::PARAM_STR);
        $statement->bindValue('mail', $data['mail'], \PDO::PARAM_STR);
        $statement->bindValue('picture', $data['picture'], \PDO::PARAM_STR);

        $statement->execute();
    }
}
