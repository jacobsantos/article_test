<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ExpectJwt
{
    protected const AUTHORIZATION_BEARER = "Bearer ";

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->get('Authorization', null);
        if (!$authorization)
        {
            throw new AuthenticationException();
        }
        $this->checkUsersToken($authorization);
        return $next($request);
    }

    /**
     * @param string $authorization
     * @throws AuthenticationException
     */
    protected function checkUsersToken(string $authorization)
    {
        if (!$this->hasBearer($authorization))
        {
            throw new AuthenticationException();
        }

        $token = substr($authorization, strlen(static::AUTHORIZATION_BEARER));
        try
        {
            // We only require that the token exist to continue.
            User::token($token)->firstOrFail();
        }
        catch (ModelNotFoundException $exception)
        {
            throw new AuthenticationException();
        }
    }

    /**
     * Accept either 'bearer ' or 'Bearer '.
     *
     * @param string $authorization
     * @return bool
     */
    protected function hasBearer(string $authorization)
    {
        $length = strlen(static::AUTHORIZATION_BEARER);
        $test = strtolower(substr($authorization, 0, $length));
        return $test === strtolower(static::AUTHORIZATION_BEARER);
    }
}
