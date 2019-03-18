<?php

namespace Modules\Core\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = 'Modules\Core\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapCommandRoutes();

        $this->mapScheduleRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(__DIR__.'/../Routes/web.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(__DIR__.'/../Routes/api.php');
    }

    /**
     * Define the "console" routes for the application.
     *
     * These routes are console commands.
     *
     * @return void
     */
    protected function mapCommandRoutes()
    {
        $commands = require __DIR__.'/../Routes/console.php';
        $this->commands($commands);
    }

    /**
     * Define the "schedule" routes for the application.
     *
     * These routes are schedules.
     *
     * @return void
     */
    protected function mapScheduleRoutes()
    {
        $schedule = $this->app->make(Schedule::class);
        $closure = require __DIR__.'/../Routes/schedule.php';

        $closure($schedule);
    }
}
