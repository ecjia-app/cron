<?php

namespace Ecjia\App\Payment\Installer;

use Ecjia\Component\Plugin\Storages\PaymentPluginStorage;
use ecjia_plugin;
use RC_DB;
use RC_Plugin;

class PluginInstaller extends \Ecjia\Component\Plugin\Installer\PluginInstaller
{

    /**
     * 安装插件
     */
    public function install()
    {
        $plugin_file = RC_Plugin::plugin_basename( $this->plugin_file );

        (new PaymentPluginStorage())->addPlugin($plugin_file);

        $code = $this->getConfigByKey('pay_code');

        /* 检查输入 */
        if (empty($code)) {
            return ecjia_plugin::add_error('plugin_install_error', __('支付方式CODE不能为空', 'payment'));
        }

        $this->installByCode($code);

        return true;
    }

    /**
     * @param $code
     */
    protected function installByCode($code)
    {
        $format_name        = $this->getPluginDataByKey('Name');
        $format_description = $this->getPluginDataByKey('Description');

        /* 取得配置信息 */
        $pay_config = serialize($this->getConfigByKey('forms'));

        /* 取得和验证支付手续费 */
        $pay_fee = $this->getConfigByKey('pay_fee', 0);

        /* 安装，检查该支付方式是否曾经安装过 */
        $count = RC_DB::connection(config('cashier.database_connection', 'default'))->table('payment')->where('pay_code', $code)->count();

        if ($count > 0) {
            /* 该支付方式已经安装过, 将该支付方式的状态设置为 enable */
            $data = array(
                'pay_name'   => $format_name,
                'pay_desc'   => $format_description,
                'pay_config' => $pay_config,
                'pay_fee'    => $pay_fee,
                'enabled'    => 1
            );

            RC_DB::connection(config('cashier.database_connection', 'default'))->table('payment')->where('pay_code', $code)->update($data);
        }
        else {
            /* 该支付方式没有安装过, 将该支付方式的信息添加到数据库 */
            $data = array(
                'pay_code'   => $code,
                'pay_name'   => $format_name,
                'pay_desc'   => $format_description,
                'pay_config' => $pay_config,
                'pay_fee'    => $pay_fee,
                'enabled'    => 1,
                'is_online'  => $this->getConfigByKey('is_online'),
                'is_cod'     => $this->getConfigByKey('is_cod'),
            );
            RC_DB::connection(config('cashier.database_connection', 'default'))->table('payment')->insert($data);
        }
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        $code = $this->getConfigByKey('pay_code');

        /* 检查输入 */
        if (empty($code)) {
            return ecjia_plugin::add_error('plugin_uninstall_error', __('支付方式CODE不能为空', 'payment'));
        }

        (new PluginUninstaller($code, new PaymentPluginStorage()))->uninstall();

        return true;
    }


}