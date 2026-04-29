<?php

namespace App\Listeners;

use App\Mail\WelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

/**
 * Listens for user registration events and dispatches a welcome email to
 * the newly registered user.
 *
 * The email is queued rather than sent synchronously to keep the
 * registration flow responsive.
 */
class SendWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * No dependencies are required at this time.
     */
    public function __construct()
    {
        // Currently Empty
    }

    /**
     * Handle the registered event.
     *
     * Queues a WelcomeEmail to the newly registered user's email address.
     *
     * @param  Registered $event The registration event carrying the new user.
     *
     * @return void
     */
    public function handle(Registered $event): void
    {
        Mail::to($event->user->email)
            ->queue(new WelcomeEmail($event->user));
    }
}
