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

    public function insert(array $element)
    {
        $query = "INSERT INTO " . self::TABLE . " 
            (`common_name`, `latin_name`, `color`, `picture`, `description`, `element_type_id`, `toxicity_id`)
            VALUES (:common_name, :latin_name, :color, :picture, :description, :element_type_id, :toxicity_id)";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('common_name', $element['commonName'], \PDO::PARAM_STR);
        $statement->bindValue('latin_name', $element['latinName'], \PDO::PARAM_STR);
        $statement->bindValue('color', $element['color'], \PDO::PARAM_STR);
        $statement->bindValue('picture', $element['picture'], \PDO::PARAM_STR);
        $statement->bindValue('description', $element['description'], \PDO::PARAM_STR);
        $statement->bindValue('element_type_id', $element['type'], \PDO::PARAM_INT);
        $statement->bindValue('toxicity_id', $element['toxicity'], \PDO::PARAM_INT);

        $statement->execute();
    }

    public function update(array $element)
    {
        $query = "UPDATE " . self::TABLE . " SET `common_name` = :common_name, `latin_name` = :latin_name, 
        `color` = :color, `picture` = :picture, `description` = :description, `element_type_id` = :element_type_id, 
        `toxicity_id` = :toxicity_id WHERE id = :id";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $element['id'], \PDO::PARAM_STR);
        $statement->bindValue('common_name', $element['commonName'], \PDO::PARAM_STR);
        $statement->bindValue('latin_name', $element['latinName'], \PDO::PARAM_STR);
        $statement->bindValue('color', $element['color'], \PDO::PARAM_STR);
        $statement->bindValue('picture', $element['picture'], \PDO::PARAM_STR);
        $statement->bindValue('description', $element['description'], \PDO::PARAM_STR);
        $statement->bindValue('element_type_id', $element['type'], \PDO::PARAM_INT);
        $statement->bindValue('toxicity_id', $element['toxicity'], \PDO::PARAM_INT);

        $statement->execute();
    }
}
