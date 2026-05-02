<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Artisan commands that should be disabled in protected environments.
     *
     * This list includes commands that can cause data loss or significant
     * changes to the database schema, such as dropping tables or resetting
     * the database. By overriding these commands with safe handlers, we can
     * prevent accidental execution of destructive operations in production,
     * staging, and QA environments, while still allowing them to be used in
     * development environments where such operations may be necessary for
     * testing and development purposes.
     */
    private const DESTRUCTIVE_COMMANDS = [
        'migrate:fresh',    // Drops all tables
        'migrate:reset',    // Rolls back all migrations
        'migrate:rollback', // Rolls back a batch of migrations
        'db:wipe',         // Drops all databases
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Currently empty
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all other service providers have been
     * registered, meaning you have access to all other services that have
     * been registered by the framework. It is intended for performing any
     * actions required during the bootstrapping of the application, such
     * as registering model observers, defining gates, and configuring
     * default behaviors. In this implementation, we register the
     * UserObserver to listen for events on the User model, configure
     * default settings for production environments, and set up a global
     * gate to allow users with the "Super Admin" role to bypass all
     * permission checks. Additionally, we conditionally disable
     * destructive Artisan commands in production, staging, and QA
     * environments to prevent accidental data loss during development
     * and testing.
     *
     * @return void
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        $this->configureDefaults();
        $this->configureSuperAdminGate();
        $this->preventDestructiveCommandsInProtectedEnvironments();
    }

    /**
     * Configure default behaviors for production-ready applications.
     *
     * This method sets up defaults such as using immutable dates, enforcing
     * strong password rules in production, and prohibiting destructive
     * database commands. It is designed to be called during the
     * bootstrapping of the application to ensure that these defaults are
     * applied consistently across all environments, with stricter rules in
     * production for enhanced security and stability. Non-production
     * environments will have more lenient defaults to facilitate development
     * and testing.
     *
     * @return void
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);
        DB::prohibitDestructiveCommands(app()->isProduction());
        $this->configurePasswordDefaults();
    }

    /**
     * Configure password validation defaults based on environment.
     *
     * @return void
     */
    protected function configurePasswordDefaults(): void
    {
        $rules = Password::min(12)
            ->mixedCase()
            ->letters()
            ->numbers()
            ->symbols()
            ->uncompromised();

        Password::defaults(
            fn (): ?Password => app()->isProduction() ? $rules : null
        );
    }

    /**
     * Grant Super Admin role all permissions implicitly.
     *
     * This works in the app by using gate-related functions
     * like auth()->user->can() and @can()
     *
     * @return void
     */
    protected function configureSuperAdminGate(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions
        // like auth()->user->can() and @can()
        Gate::before(fn ($user) => $user->hasRole('Super Admin') ? true : null);
    }

    /**
     * Conditionally prevent destructive commands in protected environments.
     *
     * @return void
     */
    protected function preventDestructiveCommandsInProtectedEnvironments(): void
    {
        if (! app()->environment('production', 'staging', 'qa')) {
            return;
        }

        array_map(
            fn ($command) => $this->disableDestructiveCommand($command),
            self::DESTRUCTIVE_COMMANDS
        );
    }

    /**
     * Disable a specific destructive command.
     *
     * @param string $command
     *
     * @return void
     */
    private function disableDestructiveCommand(string $command): void
    {
        Artisan::command($command, function () use ($command) {
            /** @var Command $this */
            $this->error(
                "The '{$command}' command is disabled in this
                environment for safety."
            );
        });
    }
}
