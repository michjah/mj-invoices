<?php

namespace App\Models;

use Database\Factories\ContactsFactory;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    /** @use HasFactory<ContactsFactory> */
    use HasFactory;

    private Connection $dbConnection;

    protected $table = 'contacts';

    protected $guarded = [];

    protected $fillable = [
        'name',
        'ic',
        'dic',
        'contact_person',
        'contact_person_email',
        'contact_person_phone',
        'mail',
        'phone',
        'web',
        'address_city',
        'address_street',
        'address_zipcode',
        'bank_swift',
        'bank_iban',
        'bank_number',
        'note',
        'currency',
    ];

    public $timestamps = false;

    public $validationData = [
        'name' => 'required|string|max:255',
        'ic' => 'required|numeric|digits:9|min:1',
        'dic' => 'required|string|max:30',
        'contact_person' => 'required|string|max:255',
        'contact_person_email' => 'optional|email',
        'contact_person_phone' => 'optional|string|max:255',
        'mail' => 'required|email',
        'phone' => 'required|string|max:255',
        'web' => 'optional|url',
        'address_city' => 'required|string|max:255',
        'address_street' => 'required|string|max:255',
        'address_zipcode' => 'required|string|max:255',
        'bank_swift' => 'optional|string|max:255',
        'bank_iban' => 'optional|string|max:255',
        'bank_number' => 'optional|string|max:255',
        'note' => 'optional|string',
        'currency' => 'required|string|min:3|max:3',
    ];

    // Explicitně připojení factory
    protected static function newFactory()
    {
        return ContactsFactory::new();
    }
}
