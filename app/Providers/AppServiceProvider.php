<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Event::listen(function (\Illuminate\Auth\Events\Login $event) {
            /** @var \App\Models\User $user */
            $user = $event->user;
            $user->timestamps = false;
            $user->forceFill([
                'last_login_at' => now(),
                'last_active_at' => now(),
            ])->save();
            $user->timestamps = true;
        });

        \Illuminate\Support\Facades\Event::listen(function (\Illuminate\Auth\Events\Logout $event) {
            if ($event->user) {
                /** @var \App\Models\User $user */
                $user = $event->user;
                $user->timestamps = false;
                $user->forceFill([
                    'last_active_at' => null,
                ])->save();
                $user->timestamps = true;
            }
        });

        \Illuminate\Support\Facades\View::composer(['components.layout.header', 'health-records._form'], function ($view) {
            $view->with('globalCompanies', \App\Models\Company::where('is_active', true)->orderBy('name')->get());
        });
    }
}
