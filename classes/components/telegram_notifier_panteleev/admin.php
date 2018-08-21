<?php

use UmiCms\Service;

class Telegram_notifier_panteleevAdmin
{
    use baseModuleAdmin;
    /**
     * @var dummy $module
     */
    public $module;

    /**
     * Возвращает настройки модуля.
     * Если передано ключевое слово "do" в $_REQUEST['param0'],
     * то сохраняет переданные настройки.
     */
    public function config() {
        $regedit = Service::Registry();

        $params = [];
        /*$params = [
            'config' => [
                'boolean:tg_enable_notification' => null,
                'string:tg_bot_api_key' => null,
                'string:tg_chat_id' => null,
                'string:cz_webhook_url' => null,
            ]
        ];*/

        $params['config']['boolean:tg_enable_notification'] = (bool) $regedit->get('//modules/telegram_notifier_panteleev/tg_enable_notification');
        $params['config']['string:tg_bot_api_key'] = $regedit->get('//modules/telegram_notifier_panteleev/tg_bot_api_key');
        $params['config']['string:tg_chat_id'] = $regedit->get('//modules/telegram_notifier_panteleev/tg_chat_id');
        $params['config']['string:cz_webhook_url'] = $regedit->get('//modules/telegram_notifier_panteleev/cz_webhook_url');

        $mode = getRequest('param0');
        if ($mode == 'do'){
            $params = $this->expectParams($params);

            $tgEnableNotification = $params['config']['boolean:tg_enable_notification'];
            $tgBotApiKey = $params['config']['string:tg_bot_api_key'];
            $tgChatId = $params['config']['string:tg_chat_id'];
            $czWebhookUrl = $params['config']['string:cz_webhook_url'];

            $regedit->set('//modules/telegram_notifier_panteleev/tg_enable_notification', $tgEnableNotification);
            $regedit->set('//modules/telegram_notifier_panteleev/tg_bot_api_key', $tgBotApiKey);
            $regedit->set('//modules/telegram_notifier_panteleev/tg_chat_id', $tgChatId);
            $regedit->set('//modules/telegram_notifier_panteleev/cz_webhook_url', $czWebhookUrl);

            $this->chooseRedirect();
        }

        $this->setDataType('settings');
        $this->setActionType('modify');

        $data = $this->prepareData($params, 'settings');
        $this->setData($data);
        $this->doData();
    }
}