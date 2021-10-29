<?php namespace App\Models;

use Exception;
use PDO;

class Model
{
    protected string $table_name;
    protected array $associated_tables = [];
    protected PDO $connection;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param string[] $fields
     * @return array|false
     */
    public function all(array $fields = ['*'], $with = [])
    {
        try {
            $fields = implode(',', $fields);
            $query = "select {$fields} 
                        from {$this->table_name}   
                        order by {$this->table_name}.created_at";
            $statement = $this->connection->prepare($query);
            $statement->execute();
            $contacts = $statement->fetchAll(PDO::FETCH_OBJ);
            if (sizeof($with) > 0)
                $contacts = $this->with($contacts, $with);
        } catch (Exception $exception) {
            $contacts = [];
        }
        return $contacts;
    }

    /**
     * @param $id
     * @param string[] $fields
     * @return mixed|null
     */
    public function find($id, array $fields = ['*']) {
        try {
            $fields = collect($fields)->join(",");
            $query = "select {$fields} 
                        from {$this->table_name}
                        where {$this->table_name}.id = :id
                        order by {$this->table_name}.created_at
                        limit 1";
            $statement = $this->connection->prepare($query);
            $statement->execute(["id" => $id]);
            $contact = $statement->fetch(PDO::FETCH_OBJ);
        } catch (Exception $exception) {
            $contact = null;
        }
        return $contact;
    }

    /**
     * @param $data
     * @return mixed|null
     */
    public function create($data)
    {
        $fields = collect($data)->keys();
        $values = $fields->map(function ($field) { return ":$field"; })->join(",");
        try {
            $query = "insert into {$this->table_name} (". $fields->join(",") .") values ($values)";
            $statement = $this->connection->prepare($query);
            $statement->execute($data);
            $contact = $this->find($this->connection->lastInsertId());
        } catch (Exception $exception) {
            $contact = null;
        }
        return $contact;
    }

    public function update($id, $data)
    {
        $fields = str_replace(',,', ',', collect($data)->keys()->map(function ($field) use($data) {
            if (!is_array($data[$field]))
                return "$field=:$field";
        })->join(","));
        try {
            $query = "update {$this->table_name} set {$fields} where {$this->table_name}.id = {$id}";
            $statement = $this->connection->prepare($query);
            $statement->execute(collect($data)->filter(function ($value) { return !is_array($value); })->toArray());
            $contact = $this->find($id);
        } catch (Exception $exception) {
            $contact = null;
        }
        return $contact;
    }

    public function delete($id): bool
    {
        try {
            foreach ($this->associated_tables as $table_name)
            {
                $query = "delete from {$table_name} where {$table_name}.contact_id = :contact_id";
                $statement = $this->connection->prepare($query);
                $statement->execute(["contact_id" => $id]);
            }
            $query = "delete from {$this->table_name} where {$this->table_name}.id = :id";
            $statement = $this->connection->prepare($query);
            return $statement->execute(["id" => $id]);
        } catch (Exception $exception) {
            return false;
        }
    }

    protected function with($records, $with = [])
    {
        $IDs = collect($records)->pluck('id')->join(',');
        foreach ($with as $table_name) {
            $query = "select * 
                        from {$table_name}
                        where {$table_name}.contact_id in ({$IDs})
                        order by {$table_name}.created_at";
            $statement = $this->connection->prepare($query);
            $statement->execute();
            $data = collect($statement->fetchAll(PDO::FETCH_OBJ))->groupBy('contact_id');
            $records = collect($records)->map(function ($record) use ($data, $table_name) {
                $record->{$table_name} = $data->get($record->id) ?? collect([]);
                return $record;
            });
        }
        return $records;
    }
}