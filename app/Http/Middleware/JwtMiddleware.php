<?php

    namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

    class JwtMiddleware extends BaseMiddleware
    {

        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next, $role = null)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return jsend_error(['status' => 'Token is Invalid']);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return jsend_error(['status' => 'Token is Expired']);
                }else{
                    return jsend_error(['status' => 'Authorization Token not found']);
                }
            }
            if ($role !== null && $user->role !== "superadmin" && !in_array($user->role,explode('|',$role))) {
                return jsend_error(['status' => 'Access is forbidden for this role']);
            }
            return $next($request);
        }
    }