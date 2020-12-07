<?php


namespace Ecjia\App\Cron;


use ecjia_error;
use RC_Cron;

class CronJobManager
{
    /**
     * @var CronPlugin
     */
    private $plugin;

    /**
     * @var \ecjia_error
     */
    private $error;

    /**
     * CronJobManager constructor.
     * @param CronPlugin|null $plugin
     */
    public function __construct(?CronPlugin $plugin = null)
    {
        $this->plugin = $plugin ?: new CronPlugin();
        $this->error = new ecjia_error();
    }


    public function getCronJobs()
    {
        return RC_Cron::getCronJobs();
    }


    public function addCronJobs()
    {
        $list = $this->plugin->getEnableList();
        collect($list)->each(function ($item) {
            $this->addCronJobHandle($item);
        });
    }


    protected function addCronJobHandle($item)
    {
        try {
            if ($item['cron_expression']) {
                RC_Cron::add($item['cron_code'], $item['cron_expression'], function () use ($item) {
                    return $this->runBy($item['cron_code']);
                });
            }
        } catch (\Exception $e) {
            $this->writeErrorLog($e);
        }
    }

    /**
     * 保存错误日志
     * @param \Exception $exception
     */
    protected function writeErrorLog(\Exception $exception)
    {
        $message = sprintf("%s %s", get_class($exception), $exception->getMessage());
        $this->error->add(get_class($exception), $exception->getMessage());
        ecjia_log_error($message, [], 'cron');
    }


}