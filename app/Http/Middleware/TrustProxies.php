<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    public function __construct()
    {
        $proxies = env('TRUSTED_PROXIES');

        if ($proxies === '*') {
            $this->proxies = '*';
        } elseif (is_string($proxies) && trim($proxies) !== '') {
            $this->proxies = array_map('trim', explode(',', $proxies));
        } else {
            $this->proxies = null;
        }

        $headers = env('TRUSTED_PROXY_HEADERS');
        if (is_string($headers) && trim($headers) !== '') {
            // allow users to specify header constants like "FORWARDED,X_FORWARDED_FOR"
            $map = [
                'FORWARDED' => Request::HEADER_FORWARDED,
                'X_FORWARDED_FOR' => Request::HEADER_X_FORWARDED_FOR,
                'X_FORWARDED_HOST' => Request::HEADER_X_FORWARDED_HOST,
                'X_FORWARDED_PORT' => Request::HEADER_X_FORWARDED_PORT,
                'X_FORWARDED_PROTO' => Request::HEADER_X_FORWARDED_PROTO,
                'X_FORWARDED_AWS_ELB' => Request::HEADER_X_FORWARDED_AWS_ELB,
            ];

            $value = 0;
            foreach (array_map('trim', explode(',', $headers)) as $h) {
                if (isset($map[$h])) {
                    $value |= $map[$h];
                }
            }

            if ($value !== 0) {
                $this->headers = $value;
            }
        }
    }
}
