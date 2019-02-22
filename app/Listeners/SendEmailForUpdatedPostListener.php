<?php

namespace App\Listeners;

use App\Events\PostWasUpdated;
use App\Jobs\SendEmailThatPostWasUpdated;

class SendEmailForUpdatedPostListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PostWasUpdated $event)
    {
        SendEmailThatPostWasUpdated::dispatch($event->post);
    }
}
