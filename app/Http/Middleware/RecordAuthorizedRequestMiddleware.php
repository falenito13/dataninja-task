<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserRequestLog;
use App\Models\UserToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RecordAuthorizedRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if ($request->get('access_token')) {
                $accessToken = $request->get('access_token');
            } else {
                $accessToken = str_replace('Bearer ','',$request->header('Authorization'));
            }
            $userTokenId = UserToken::where('access_token', $accessToken)
                ->first()->id;
            UserRequestLog::create([
                'user_id' => auth()->guard('token')->id(),
                'token_id' => $userTokenId,
                'request_method' => $request->getMethod(),
                'request_params' => json_encode($request->all())
            ]);
            auth()->user()->increment('requests_count');
        }
        return $next($request);
    }
}
