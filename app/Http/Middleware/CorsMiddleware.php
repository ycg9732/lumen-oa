<?php namespace App\Http\Middleware;

class CorsMiddleware {

    public function handle($request, \Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'HEAD, GET, POST, PUT, PATCH, DELETE',
//            'Access-Control-Allow-Headers'     => $request->header('Access-Control-Request-Headers')
        ];
        if ($request->isMethod('OPTIONS')){
            return response('ok', 200, $headers);
        }


        $response = $next($request);

        $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
//        $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
        $response->header('Access-Control-Allow-Origin', '*');

        return $response;
    }

}
