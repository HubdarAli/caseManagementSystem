<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MenuComponentMiddleware
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
        $selectedComponent = Session::get('selected_component');

        $currentPath = parse_url(\Illuminate\Support\Facades\Request::url(), PHP_URL_PATH);

        $menuItem = Permission::where('permission_link', $currentPath)->first();

        if (!$menuItem) {
            $parts = explode('/', trim($currentPath, '/'));

            while (count($parts) > 1) {
                array_pop($parts);
                $parentPath = '/' . implode('/', $parts);

                $menuItem = Permission::where('permission_link', $parentPath)->first();

                if ($menuItem) {
                    break;
                }
            }
        }

        $menuItem = $menuItem->parent ?? $menuItem;

        if ($menuItem && $menuItem->component) {
            $selectedComponent = $menuItem->component;
            Session::put('selected_component', $selectedComponent);
        }

        // if (empty($selectedComponent)) return to_route('dashboard');

        return $next($request);
    }
}
