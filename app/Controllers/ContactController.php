<?php namespace App\Controllers;

use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contact_model = new Contact();
        $json = json_encode($contact_model->all(['*'], ['contact_numbers']));
        $this->response($json, 200);
    }

    public function store()
    {

    }
}