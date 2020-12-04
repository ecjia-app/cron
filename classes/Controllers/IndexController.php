<?php


namespace Ecjia\App\Cron\Controllers;


use Ecjia\System\BaseController\BasicController;
use Ecjia\App\Cron\CronRun;

class IndexController extends BasicController
{

    public function init()
    {
        (new CronRun())->run();

        return response('ECJia cron job running...');
    }

}