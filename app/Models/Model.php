<?php namespace App\Models;

use Exception;
use PDO;

class Model
{
    protected string $table_name;
    protected $connection;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    }


}