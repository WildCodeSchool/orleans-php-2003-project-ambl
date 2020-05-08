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
     * Name of table
     */
    const TABLE = 'element';

    /**
     * Number of results to display
     */
    const MAX_RESULT = 12;

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
    public function selectAll(): array
    {
        $query = "SELECT " . self::TABLE . ".*, toxicity.name toxicity_name FROM " . self::TABLE . "
                    JOIN toxicity ON toxicity.id=element.toxicity_id
                    ORDER BY element.common_name LIMIT " . self::MAX_RESULT;

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectOneAtRandom(): array
    {
        $query = 'SELECT ' . self::TABLE . '.*, toxicity.name toxicity_name 
                  FROM ' . self::TABLE . '
                  JOIN toxicity ON toxicity.id=element.toxicity_id
                  ORDER BY RAND()
                  LIMIT 1';

        return $this->pdo->query($query)->fetch();
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
