<?php

namespace App\DTOs\Auth;

class LoginDTO
{

    public string $email, $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }


}
