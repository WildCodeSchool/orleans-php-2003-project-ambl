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
class BooksManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'starter_books_mycology';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll() : array
    {
        $query = 'SELECT title, author, year, publisher, name FROM books JOIN book_type ON book_type.id=books.type_id';
    
        return $this->pdo->query($query)->fetchAll();
    }

}