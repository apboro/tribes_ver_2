<?php

namespace App\Http\Middleware;

use App\Models\Community;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnedGroupCommunity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();

        $community = $request->route('community');
        if($community == 'all'){
            return $response;
        }else if (is_string($community) && strlen($community) > 0) {
            $communityIds = explode ('-',$community);
            $communityIds = array_filter($communityIds);
            if(empty($communityIds)){
                abort(403);
            }
            foreach (Community::whereIn('id',$communityIds)->get() as $eachCommunity) {
                if(!$eachCommunity->isOwnedByUser($user)){
                    abort(403);
                }
            }
        } else {
            abort(403);
        }

        return $response;
    }
}
