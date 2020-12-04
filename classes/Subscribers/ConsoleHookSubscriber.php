<?php


namespace Ecjia\App\Cron\Subscribers;


use Ecjia\App\Cron\CronRun;
use ecjia_admin;
use RC_Cron;
use RC_Uri;
use Royalcms\Component\Hook\Dispatcher;

class ConsoleHookSubscriber
{


    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        // Log only error jobs to database
        RC_Cron::setLogOnlyErrorJobsToDatabase(true);
        // Add a cron job
        (new CronRun())->addCronJobs();
    }

}