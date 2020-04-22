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
    public function selectAllByType(string $type): array
    {
        if ($type == '') {
            $type = 'mushroom';
        }

        $query = 'SELECT element.name, element.picture FROM ' . $this->table . ' JOIN type ON ' . $this->table;
        $query .= '.type_id = type.id WHERE type.name = "' . $type . '" LIMIT ' . self::MAX_RESULT;

        return $this->pdo->query($query)->fetchAll();
    }
}
