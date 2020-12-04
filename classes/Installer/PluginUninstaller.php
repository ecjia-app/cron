<?php


namespace Ecjia\App\Payment\Installer;


use RC_DB;

class PluginUninstaller extends \Ecjia\Component\Plugin\Installer\PluginUninstaller
{

    public function uninstallByCode($code)
    {
        /* 从数据库中删除支付方式 */
        RC_DB::connection(config('cashier.database_connection', 'default'))->table('payment')->where('pay_code', $code)->delete();
    }

}