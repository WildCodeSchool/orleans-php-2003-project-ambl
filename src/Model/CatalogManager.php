<?php

/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class CatalogManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'element';

    const MAX_RESULT = 12;

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Recover data from the database according to the type of element (mushroom or plant)
     * @param string $type
     * @return array
     */
    public function selectAllByType(string $type = 'mushroom'): array
    {
        $query = 'SELECT element.name, element.picture FROM ' . $this->table . ' JOIN type ON ' . $this->table;
        $query .= '.type_id = type.id WHERE type.name = ":type" LIMIT ' . self::MAX_RESULT;

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':type', $type, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }
}
