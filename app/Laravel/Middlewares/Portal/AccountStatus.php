<?php

namespace App\Laravel\Middlewares\Portal;

use Auth;
use Closure;
use Illuminate\Http\RedirectResponse;

class AccountStatus{

    public function handle($request, Closure $next){
        $user = auth('web')->user();

        if ($user && $user->status == "inactive") {
            auth('web')->logout();

            session()->flash('notification-status', "warning");
			session()->flash('notification-msg', "Account status inactive.");

            return new RedirectResponse(route('portal.auth.login'));
        }

        return $next($request);
    }
}
