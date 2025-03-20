<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Response;
use App\Observers\TicketObserver;
use App\Policies\ResponsePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Make sure this matches your model namespace exactly
        Response::class => ResponsePolicy::class,
    ];
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies(): void
    {
        foreach ($this->policies as $key => $value) {
            \Gate::policy($key, $value);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive(); // pagination style (page 1, page 2, etc.)
        Ticket::observe(TicketObserver::class);
        $this->registerPolicies();
    }
}
