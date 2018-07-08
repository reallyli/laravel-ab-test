<?php

namespace Reallyli\AB;

use Reallyli\AB\Commands\ExportCommand;
use Reallyli\AB\Commands\FlushCommand;
use Reallyli\AB\Commands\InstallCommand;
use Reallyli\AB\Commands\ReportCommand;
use Reallyli\AB\Session\CookieSession;
use Illuminate\Support\ServiceProvider;

class TesterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__).'/config/config.php' => config_path('ab.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportCommand::class,
                FlushCommand::class,
                InstallCommand::class,
                ReportCommand::class
            ]);
        }
    }
    
    /**
     * Register the application events.
     *
     * @return mixed
     */
    public static function setTrack($request)
    {
        return (new TesterServiceProvider())->app['ab']->track($request);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ab', function () {
            return new Tester(new CookieSession);
        });
    }
}
