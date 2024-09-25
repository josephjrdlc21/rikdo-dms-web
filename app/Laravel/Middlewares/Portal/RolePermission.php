<?php

namespace App\Laravel\Middlewares\Portal;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Guard;

class RolePermission{

    public function handle($request, Closure $next, $permission, $guard = null){
        $guard = 'portal';
        $authGuard = Auth::guard($guard);

        $user = $authGuard->user();

        if (!$user) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (!method_exists($user, 'hasAnyPermission')) {
            throw UnauthorizedException::missingTraitHasRoles($user);
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        if (!$user->canAny($permissions,$guard)) {
            throw UnauthorizedException::forPermissions($permissions);
        }

        return $next($request);
    }

    /**
     * Specify the permission and guard for the middleware.
     *
     * @param  array|string  $permission
     * @param  string|null  $guard
     * @return string
     */
    public static function using($permission, $guard = null){
        $permissionString = is_string($permission) ? $permission : implode('|', $permission);
        $args = is_null($guard) ? $permissionString : "$permissionString,$guard";

        return static::class.':'.$args;
    }
}
