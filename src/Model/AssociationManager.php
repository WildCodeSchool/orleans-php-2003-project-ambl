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
        $query ='SELECT * FROM council';
        return $this->pdo->query($query)->fetchAll();
    }
}
