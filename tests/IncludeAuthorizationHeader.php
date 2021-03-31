<?php


namespace Tests;


trait IncludeAuthorizationHeader
{
    use CreatesUser;

    protected function withAuthorizationHeaderByUser($user)
    {
        return $this->withHeaders(['Authorization' => "Bearer {$user->token}"]);
    }

    protected function withAuthorizationHeader()
    {
        $token = CreatesJwt::makeRandom();
        $this->makeSingleUserWithToken($token);
        return $this->withHeaders(['Authorization' => "Bearer $token"]);
    }

    protected function withInvalidAuthorizationHeader()
    {
        $token = CreatesJwt::makeRandom();
        return $this->withHeaders(['Authorization' => "Bearer $token"]);
    }
}
