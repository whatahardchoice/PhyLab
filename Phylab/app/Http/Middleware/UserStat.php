<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\UserStatModel;

class UserStat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response=$next($request);
        $uid=null;
        if(Auth::check())
            $uid=Auth::user()->id;
        else
            $uid=null;
        $path = $request->path();
        $type = $request->method();
        UserStatModel::create([
            'uid'=>$uid,
            'path'=>$path,
            'type'=>$type
        ]);
        return $response;
    }
}
