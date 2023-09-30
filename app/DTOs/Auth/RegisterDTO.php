<?php

namespace App\DTOs\Auth;

class RegisterDTO
{

    public string $name, $email, $password;

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }


}
