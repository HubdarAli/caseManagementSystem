<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    // public function boot()
    // {
    //     view()->composer('*', function ($view) {
    //         $menus = Permission::with([
    //             'subMenus' => function ($query) {
    //                 $query->where('permission_type', 'menu');
    //             }
    //         ])
    //             ->where('permission_type', 'menu')
    //             ->whereNull('parent_id')
    //             ->orderBy('sort_by','asc')
    //             ->get();

    //         $view->with('menus', $menus);
    //     });
    // }

    public function boot()
    {
        view()->composer('*', function ($view) {
            $selectedComponent = Session::get('selected_component');

            $currentPath = parse_url(Request::url(), PHP_URL_PATH);

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

            $menus = Permission::with([
                'subMenus' => function ($query) {
                    $query->where('permission_type', 'menu');
                }
            ])
                ->where('permission_type', 'menu')
                ->whereNull('parent_id')
                // ->where(function ($query) use ($selectedComponent) {
                //     $query->where('component', $selectedComponent);
                // })
                ->orderBy('sort_by', 'asc')
                ->get();


            $view->with('menus', $menus);
        });
    }
}
