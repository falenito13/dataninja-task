<?php

namespace App\Services;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthService
{

    public function generateToken(): array
    {
        try {
            $userToken = UserToken::create([
                'user_id' => auth()->id(),
                'access_token' => Str::random(64),
                'expires_at' => now()->addDays(30)
            ]);
            return [
                'success' => true,
                'status' => Response::HTTP_CREATED,
                'data' => $userToken
            ];
        } catch (\Exception $ex) {
            Log::debug($ex->getMessage());
            return [
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => __('Something went wrong!')
            ];
        }
    }

    public function deleteToken(UserToken $userToken)
    {
        try {
            $userToken->delete();
            return [
                'success' => true,
                'status' => Response::HTTP_NO_CONTENT,
                'message' => __('Token successfully deleted!')
            ];
        } catch (\Exception $ex) {
            return [
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => __('Something went wrong!')
            ];
        }
    }

    public function register(RegisterDTO $registerDTO): array
    {
        $user = User::create([
            'name' => $registerDTO->name,
            'email' => $registerDTO->email,
            'password' => bcrypt($registerDTO->password)
        ]);
        $loginDTO = new LoginDTO($registerDTO->email, $registerDTO->password);
        return $this->login($loginDTO);
    }

    public function login(LoginDTO $loginDTO)
    {
        $user = User::where('email', $loginDTO->email)->first();
        if ($user) {
            if (Hash::check($loginDTO->password, $user->password)) {
                $data = [
                    'name' => $user->name,
                    'email' => $user->email
                ];
                $userToken = UserToken::create([
                    'user_id' => $user->id,
                    'access_token' => Str::random(config('auth.access_token_length')),
                    'expires_at' => now()->addDays(config('auth.login_expiration_in_days'))
                ]);
                $data['access_token'] = $userToken->access_token;
                return [
                    'success' => true,
                    'code' => Response::HTTP_OK,
                    'data' => $data
                ];
            } else {
                return [
                    'success' => false,
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'data' => __('Incorrect email or password')
                ];
            }
        }
        return [
            'success' => false,
            'code' => Response::HTTP_UNAUTHORIZED,
            'data' => __('Incorrect email or password')
        ];
    }

    public function getUserByToken()
    {
        return User::whereHas('tokens', function ($query) {
            $bearerToken = str_replace('Bearer ','',request()->header('Authorization'));
            return $query->whereIn('access_token', [
                $bearerToken,
                request()->get('access_token')
            ]);
        })->first();
    }

}
