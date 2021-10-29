<?php namespace App\Models;

use Exception;
use PDO;

class Contact extends Model
{
    protected string $table_name = 'contacts';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string[] $fields
     * @return array|false
     */
    public function all(array $fields = ['*'], $with = [])
    {
        $contacts = [];
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
        }
        return $contacts;
    }

    private function with ($records, $with = []) {
        $IDs = collect($records)->pluck('id')->join(',');
        foreach ($with as $table_name) {
            $query = "select * 
                        from {$table_name}
                        where {$table_name}.contact_id in ({$IDs})
                        order by {$table_name}.created_at";
            $statement = $this->connection->prepare($query);
            $statement->execute();
            $data = collect($statement->fetchAll(PDO::FETCH_OBJ))->groupBy('contact_id');
            $records = collect($records)->map(function ($record) use($data, $table_name) {
                $record->{$table_name} = $data->get($record->id) ?? collect([]);
                return $record;
            });
        }
        return $records;
    }
}