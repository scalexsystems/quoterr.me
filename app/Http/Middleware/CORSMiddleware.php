<?php

namespace Quoterr\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CORSMiddleware
{

    protected $headers = [
        'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
        'Access-Control-Allow-Headers'     => 'X-Requested-With, Content-Type, X-Auth-Token, Origin, Authorization, Accepts, X-CSRF-TOKEN, *',
        'Access-Control-Expose-Headers'    => 'Authorization, Location, Access-Control-Max-Age',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Max-Age'           => '600',
        'Cache-Control'                    => 'no-cache, private, max-age=600',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);
        $origin = $request->header('Origin');
        if (Str::is('*zero.school*', $origin) or Str::is('*quoterr.me', $origin)) {
            $this->headers['Access-Control-Allow-Origin'] = $origin;
        }
        if ($response) {
            $response->headers->add($this->headers);
        } else {
            foreach ($this->headers as $key => $value) {
                header("{$key}: {$value}");
            }
        }

        return $response;
    }
}
