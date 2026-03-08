<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Entity\Contact;
use App\Models\Contacts;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(private readonly Contacts $contactsModel) {}

    public function store(Request $request)
    {
        $validatedData = $request->validate($this->contactsModel->validationData);
        return response()->json($this->contactsModel->create($validatedData), 201);
    }

    public function index() {
        $contacts = $this->contactsModel->all();
        return response()->json(['data' => $contacts, 'total' => count($contacts)]);
    }

    public function add(array $data) {
        $contact = new Contact();
        $contact->setName($data['name']);
        $contact->setIc($data['ic']);
        $contact->setDic($data['dic']);
        $contact->setContactPerson($data['contact_person']);
        $contact->setContactPersonPhone($data['contact_person_phone']);
        $contact->setContactPersonMail($data['contact_person_mail']);
        $contact->setMail($data['mail']);
        $contact->setPhone($data['phone']);
        $contact->setWeb($data['web']);
        $contact->setAddressCity($data['address_city']);
        $contact->setAddressStreet($data['address_street']);
        $contact->setAddressZipcode($data['address_zipcode']);
        $contact->setNote($data['note']);
        $contact->setBankNumber($data['bank_number']);
        $contact->setBankIban($data['bank_iban']);
        $contact->setBankSwift($data['bank_swift']);

        return response()->json($this->contactsModel->addNew($contact));
    }

    public function show(Contacts $contact) {
        return response()->json($contact);
    }

    public function update(Request $request) {
        $validatedData = $request->validate([]);
        return response()->json($this->contactsModel->update($validatedData));
    }
}
