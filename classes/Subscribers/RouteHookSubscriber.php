<?php


namespace Ecjia\App\Cron\Subscribers;


use RC_Route;
use Royalcms\Component\Hook\Dispatcher;

class RouteHookSubscriber
{


    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        // Build in Cron run route
        RC_Route::get('cron.php', 'Ecjia\App\Cron\Controllers\CronRunController@init');

    }

}