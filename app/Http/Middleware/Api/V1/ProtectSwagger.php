<?php

namespace App\Http\Middleware\Api\V1;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectSwagger
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->getUser();
        $pass = $request->getPassword();

        $envUser = env('L5_SWAGGER_USERNAME');
        $envPass = env('L5_SWAGGER_PASSWORD');


        if (empty($user) || empty($pass) ||
            !hash_equals((string) $envUser, (string) $user) ||
            !hash_equals((string) $envPass, (string) $pass)
        ) {
            return response('Unauthorized', 401, ['WWW-Authenticate' => 'Basic realm="Swagger Docs"']);
        }

        return $next($request);
    }
}
