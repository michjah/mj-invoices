<?php

namespace App\Http\Entity;

class Contact
{
    /**
     * Autoincrement ID
     * @var int $id
     */
    public int $id;

    /**
     * Name of the contact
     * @var string $name
     */
    public string $name;

    /**
     * Identification number
     * @var int $ic
     */
    public int $ic;

    /**
     * DIC number
     * @var string $dic
     */
    public string $dic;

    /**
     * Name of the contact person
     * @var string $contact_person
     */
    public string $contact_person;

    /**
     * Email address of the contact person
     * @var string $contact_person_mail
     */
    public string $contact_person_mail;

    /**
     * Phone number of the contact person
     * @var string $contact_person_phone
     */
    public string $contact_person_phone;

    /**
     * Email address
     * @var string $mail
     */
    public string $mail;

    /**
     * Phone number
     * @var string $phone
     */
    public string $phone;

    /**
     * Website URL
     * @var string $web
     */
    public string $web;

    /**
     * City
     * @var string $address_city
     */
    public string $address_city;

    /**
     * Street address
     * @var string $address_street
     */
    public string $address_street;

    /**
     * Zip code
     * @var int $address_zipcode
     */
    public int $address_zipcode;

    /**
     * Note about the contact
     * @var string $note
     */
    public string $note;

    /**
     * Bank number
     * @var string $bank_number
     */
    public string $bank_number;

    /**
     * Bank SWIFT code
     * @var string $bank_swift
     */
    public string $bank_swift;

    /**
     * IBAN of the bank account
     * @var string $bank_iban
     */
    public string $bank_iban;

    public static function create($all)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIc(): int
    {
        return $this->ic;
    }

    public function setIc(int $ic): void
    {
        $this->ic = $ic;
    }

    public function getDic(): string
    {
        return $this->dic;
    }

    public function setDic(string $dic): void
    {
        $this->dic = $dic;
    }

    public function getContactPerson(): string
    {
        return $this->contact_person;
    }

    public function setContactPerson(string $contact_person): void
    {
        $this->contact_person = $contact_person;
    }

    public function getContactPersonMail(): string
    {
        return $this->contact_person_mail;
    }

    public function setContactPersonMail(string $contact_person_mail): void
    {
        $this->contact_person_mail = $contact_person_mail;
    }

    public function getContactPersonPhone(): string
    {
        return $this->contact_person_phone;
    }

    public function setContactPersonPhone(string $contact_person_phone): void
    {
        $this->contact_person_phone = $contact_person_phone;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getWeb(): string
    {
        return $this->web;
    }

    public function setWeb(string $web): void
    {
        $this->web = $web;
    }

    public function getAddressCity(): string
    {
        return $this->address_city;
    }

    public function setAddressCity(string $address_city): void
    {
        $this->address_city = $address_city;
    }

    public function getAddressStreet(): string
    {
        return $this->address_street;
    }

    public function setAddressStreet(string $address_street): void
    {
        $this->address_street = $address_street;
    }

    public function getAddressZipcode(): int
    {
        return $this->address_zipcode;
    }

    public function setAddressZipcode(int $address_zipcode): void
    {
        $this->address_zipcode = $address_zipcode;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function getBankNumber(): string
    {
        return $this->bank_number;
    }

    public function setBankNumber(string $bank_number): void
    {
        $this->bank_number = $bank_number;
    }

    public function getBankIban(): string
    {
        return $this->bank_iban;
    }

    public function setBankIban(string $bank_iban): void
    {
        $this->bank_iban = $bank_iban;
    }

    public function getBankSwift(): string
    {
        return $this->bank_swift;
    }

    public function setBankSwift(string $bank_swift): void
    {
        $this->bank_swift = $bank_swift;
    }
}
