<?php

namespace App\Providers;

use App\Models\Backend\Products\Category;
use App\Models\Backend\Products\Product;
use App\Models\Backend\Settings\Settings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
        // backend composer
        View::composer('backend.*', function ($view) {
            $setting = Settings::instance();
            $view->with('setting', $setting);
        });
        // frontend composer
        View::composer('frontend.*', function ($view) {
            $setting = Settings::instance();
            $categories = Category::with('parent')
                ->withCount('products')
                ->parents()
                ->get();

            $products = Product::with('category')->get();
            $view->with('categories', $categories);
            $view->with('products', $products);
            $view->with('setting', $setting);
        });
    }
}
