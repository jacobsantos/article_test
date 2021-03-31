<?php

namespace Tests;

use Illuminate\Support\Str;

class CreatesJwt
{
    public function create(object $payload, string $secret): string
    {
        $header = $this->encode($this->header());
        $payload = $this->encode($payload);
        $signature = base64_encode(hash_hmac("sha256", "{$header}.{$payload}", $secret));
        return "{$header}.{$payload}.{$signature}";
    }

    /**
     * Create simple random payload.
     *
     * @return object
     */
    public function random(): object
    {
        $id = mt_rand(1000000000, 9999999999);
        return (object)[
            "sub" => (string)$id,
            "name" => "user $id",
            "iat" => now()->getTimestamp(),
        ];
    }

    /**
     * @param object $value
     * @return string
     */
    protected function encode(object $value): string
    {
        return base64_encode(urlencode(json_encode($value)));
    }

    protected function header(): object
    {
        return (object)[
            "alg" => "HS256",
            "typ" => "JWT",
        ];
    }

    public static function makeRandom(string $secret = 'secret'): string
    {
        $obj = new static();
        return $obj->create($obj->random(), $secret);
    }
}
