<?php

namespace App\Listeners;

use App\Events\OfferStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OfferStatusChanged  $event
     * @return void
     */
    public function handle(OfferStatusChanged $event)
    {
        //
    }
}
