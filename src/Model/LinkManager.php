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
class LinkManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'link';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll() : array
    {
        $query = 'SELECT * FROM '. self::TABLE;
    
        return $this->pdo->query($query)->fetchAll();
    }
}