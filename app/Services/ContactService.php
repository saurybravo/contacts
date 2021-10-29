<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\ContactNumber;
use Exception;

class ContactService
{
    private Contact $contact_model;
    private ContactNumber $contact_number_model;

    public function __construct()
    {
        $this->contact_model = new Contact();
        $this->contact_number_model = new ContactNumber();
    }

    public function get($id)
    {
        $contact = $this->contact_model->find($id);
        if ($contact === false or $contact === null) throw new Exception('Model not found');
        return $contact;
    }

    /**
     * @param $data
     * @return array|mixed
     */
    public function create($data)
    {
        $contact = $this->contact_model->create([
            "first_name" => $data["first_name"],
            "last_name" => $data["last_name"],
            "email" => $data["email"],
        ]);

        if (sizeof($data["contact_numbers"]) > 0)
            foreach ($data["contact_numbers"] as $contact_number) {
                $contact_number = ["contact_id" => $contact->id] + $contact_number;
                $contact->contact_numbers[] = $this->contact_number_model->create($contact_number);
            }

        return $contact;
    }

    /**
     * @throws Exception
     */
    public function update($data)
    {
        $contact = $this->get($data["id"]);
        if ($contact === false) throw new Exception('Model not found');
        $contact = $this->contact_model->update($contact->id, $data);
        if (sizeof($data["contact_numbers"]) > 0){
            $contact_numbers = [];
            foreach ($data["contact_numbers"] as $contact_number) {
                $contact_number_model = $this->contact_number_model->find($contact_number["id"]);
                if ($contact_number_model === false or $contact_number_model === null) throw new Exception('Model not found');
                $contact->contact_numbers[] = $this->contact_number_model->update($contact_number_model->id, (array) $contact_number);
            }
        }

        return $contact;
    }

    /**
     * @throws Exception
     */
    public function destroy($data)
    {
        $contact = $this->get($data["id"]);
        if ($contact === false) throw new Exception('Model not found');
        $result = $this->contact_model->delete($contact->id);
        if ($result === false) throw new Exception('Error trying to delete');
    }
}