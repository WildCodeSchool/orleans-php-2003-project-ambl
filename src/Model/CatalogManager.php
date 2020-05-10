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
     * @param string $search
     * @return array
     */
    public function selectAll(string $search = ''): array
    {
        $query = "SELECT " . self::TABLE . ".*, toxicity.name toxicity_name FROM " . self::TABLE . "
                    JOIN toxicity ON toxicity.id=element.toxicity_id";

        if ($search) {
            $query .= " WHERE common_name LIKE :search ORDER BY element.common_name";
        } else {
            $query .= " ORDER BY element.common_name LIMIT " . self::MAX_RESULT;
        }

        $statement = $this->pdo->prepare($query);

        if ($search) {
            $statement->bindValue('search', $search . '%');
        }

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Randomly retrieve a line
     *
     * @return array
     */
    public function selectOneAtRandom(): array
    {
        $query = "SELECT " . self::TABLE . ".*, toxicity.name toxicity_name 
                  FROM ' . self::TABLE . '
                  JOIN toxicity ON toxicity.id=element.toxicity_id
                  ORDER BY RAND()
                  LIMIT 1";

        return $this->pdo->query($query)->fetch();
    }

    /**
     * Retrieve the number of records in the table
     *
     * @return int
     */
    public function getNumberCatalogElement(): int
    {
        $query = 'SELECT id FROM ' . self::TABLE;
        $statement = $this->pdo->query($query);

        return $statement->rowCount();
    }

    /**
     * Select an element group
     *
     * @param int $pageNumber
     * @return array
     */
    public function selectByPage(int $pageNumber): array
    {
        $start = ($pageNumber - 1) * self::MAX_RESULT;
        $query = "SELECT " . self::TABLE . ".*, toxicity.name toxicity_name FROM " . self::TABLE . "
                    JOIN toxicity ON toxicity.id=element.toxicity_id
                    ORDER BY element.common_name LIMIT " . $start . ' OFFSET ' . self::MAX_RESULT;

        return $this->pdo->query($query)->fetchAll();
    }
}
