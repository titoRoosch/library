<?php

namespace App\Listeners;

use App\Mail\Notification;
use App\Events\NewBookRent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendNotificationEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewBookRent $event): void
    {
        $user = $event->getUser();

        Mail::to($user->email)->send(new Notification($user));
    }
}
