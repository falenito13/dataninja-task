<?php

namespace App\Guards;

use App\Services\AuthService;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class TokenGuard implements Guard
{
    use GuardHelpers;

    protected Request $request;
    protected AuthService $authService;

    public function __construct(Request $request, AuthService $authService)
    {
        $this->request = $request;
        $this->authService = $authService;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        return $this->authService->getUserByToken();
    }

    public
    function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }
}
