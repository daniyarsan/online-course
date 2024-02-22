<?php

namespace App\Business\DTO;


class RegistrationDto
{

    private string $username;
    private string $department;
    private string $plainPassword;


    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


    public function getUsername():string
    {
        return mb_strtolower($this->username);
    }


    public function setUsername($username): void
    {
        $this->username = mb_strtolower($username);
    }


    public function getDepartment():string
    {
        return $this->department;
    }

    public function setDepartment(string $department): void
    {
        $this->department = $department;
    }
}