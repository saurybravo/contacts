<?php namespace App\Controllers;

use App\Models\Contact;
use App\Services\ContactService;
use Exception;

class ContactController extends Controller
{
    private Contact $contact_model;
    private ContactService $contact_service;

    public function __construct()
    {
        $this->contact_model = new Contact();
        $this->contact_service = new ContactService();
    }

    public function index()
    {
        $fields = (isset($_GET['fields'])) ? explode(',', $_GET['fields']) : ['*'];
        $relations = (isset($_GET['relations'])) ? explode(',', $_GET['relations']) : [];
        $data = ($this->contact_model->all($fields, $relations));
        $this->response($data, 200);
    }

    public function store()
    {
        $inputs = $this->request();

        $this->validate($inputs, [
            "first_name" => "required|max:100",
            "last_name" => "required|max:100",
            "email" => ["required", "email", "max:150"],
            "contact_numbers" => "array",
            "contact_numbers.*.number" => "required"
        ]);

        $contact = $this->contact_service->create($inputs);

        $this->response($contact, 200);
    }

    /**
     * @throws Exception
     */
    public function update()
    {
        $inputs = $this->request();

        $this->validate($inputs, [
            "id" => "required",
            "first_name" => "required|max:100",
            "last_name" => "required|max:100",
            "email" => ["required", "email", "max:150"],
            "contact_numbers" => "array",
            "contact_numbers.*.id" => "required|numeric",
            "contact_numbers.*.number" => "required"
        ]);

        try {
            $contact = $this->contact_service->update($inputs);
            $this->response($contact, 200);
        } catch (Exception $exception) {
            $this->response(['message' => $exception->getMessage()], 422);
        }

    }

    public function show()
    {
        $inputs = $this->request();
        try {
            $this->response($this->contact_service->get($inputs['id']), 200);
        } catch (Exception $exception) {
            $this->response(['message' => $exception->getMessage()], 422);
        }
    }

    public function destroy()
    {
        $inputs = $this->request();
        try {
            $this->contact_service->destroy($inputs);
            $this->response([], 200);
        } catch (Exception $exception) {
            $this->response(['message' => $exception->getMessage()], 422);
        }
    }
}