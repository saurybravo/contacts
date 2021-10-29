<?php namespace App\Models;

class Contact extends Model
{
    protected string $table_name = 'contacts';
    protected array $associated_tables = ['contact_numbers'];

    public function __construct()
    {
        parent::__construct();
    }
}