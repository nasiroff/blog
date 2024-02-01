<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['admin.components.sidebar'], function (\Illuminate\View\View $view) {
            $view->with('userCount', User::query()->count());
            $view->with('postCount', Post::query()->count());
        });
    }
}
