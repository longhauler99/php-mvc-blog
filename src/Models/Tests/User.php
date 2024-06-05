<?php
namespace App\Models\Tests;
class User
{
    public $first_name;
    public $last_name;

    public function setFirstName($firstName): void
    {
        $this->first_name = $firstName;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setLastName($lastName): void
    {
        $this->last_name = $lastName;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}