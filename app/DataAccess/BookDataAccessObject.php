<?php

declare(strict_types=1);

namespace App\DataAccess;

use Illuminate\Database\DatabaseManager;

class BookDataAccessObject
{
    /** @var DatabaseManager **/
    protected $db;

    /** @var string **/
    protected $table = 'books';

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function find($id)
    {
        $query = $this->db->connection()
        ->table(($this->table));

        /** select **/
        $selectResult = $query
        ->select('id', 'name as title')
        ->get();

        /** where **/
        $whereResult = $query
        ->where('id', '>=', '30')
        ->orWhere('created_at', '>=', '2018-01-01')
        ->get();

        /** limit offset **/
        $limitOffsetResult = $query
        ->limit(10)
        ->offset(6)
        ->get();

        /** orderBy **/
        $orderByResult = $query
            ->orderBy('id')
            ->orderBy('updated_at', 'desc')
            ->get();

        /** join **/
        $joinResult = $query
            ->leftJoin('authors', 'books.author.id', '=', 'authors.id')
            ->leftJoin('publishers', 'books.publisher.id', '=', 'publishers.id')
            ->get();

        $result = $query
        ->select('id', 'name')
        ->get();

        foreach ($result as $book) {
            echo $book->id;
            echo $book->name;
        }

        $count = $query->count();
        echo $count;

        $query
        ->where('id', 1)
        ->update(['price' => 10000]);

        $sql = 'SELECT bookdetails.isbn, books.name'
        . 'FROM books'
        . 'LEFT JOIN bookdetails ON books.id = bookdetails.book_id'
        . 'WHERE bookdetails.price >= ? AND bookdetails.published_date >= ? '
        . 'ORDER BY bookdetails.published_date DESC';
        $results = \Illuminate\Support\Facades\DB::select($sql,['1000', '2011-01-01']);

        // $sql = 'SELECT bookdetails.isbn, books.name'
        // . 'FROM books'
        // . 'LEFT JOIN bookdetails ON books.id = bookdetails.book_id'
        // . 'WHERE bookdetails.price >= :pricee AND bookdetails.published_date >= :date '
        // . 'ORDER BY bookdetails.published_date DESC';
        // $result = \Illuminate\Support\Facades\DB::select($sql, ['price' => '1000', 'date' => '2011-01-01']);

        foreach ($result as $book) {
            echo $book->isbn;
            echo $book->name;
        }

        $sql = 'SELECT bookdetails.isbn, books.name'
        . 'FROM books'
        . 'LEFT JOIN bookdetails ON books.id = bookdetails.book_id'
        . 'WHERE bookdetails.price >= ? AND bookdetails.published_date >= ? '
        . 'ORDER BY bookdetails.published_date DESC';

        $pdo = \Illuminate\Support\Facades\DB::getPdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(['1000', '2011-01-01']);
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as $book) {
            echo $book['name'];
            echo $book['isbn'];
        }
    }
}
