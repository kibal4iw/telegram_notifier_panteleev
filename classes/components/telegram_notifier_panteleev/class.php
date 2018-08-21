<?php

use UmiCms\Service;

class telegram_notifier_panteleev extends def_module {

    /** Конструктор */
    public function __construct() {
        parent::__construct();

        if (Service::Request()->isAdmin()) {
            $this->__loadLib('admin.php');
            $this->__implement('Telegram_notifier_panteleevAdmin');

            $configTabs = $this->getConfigTabs();

            if ($configTabs) {
                $configTabs->add('config');
            }

            $this->loadAdminExtension();

            $this->__loadLib('customAdmin.php');
            $this->__implement('Telegram_notifier_panteleevCustomAdmin', true);
        }

        $this->loadSiteExtension();

        $this->__loadLib('customMacros.php');
        $this->__implement('Telegram_notifier_panteleevCustomMacros', true);

        $this->__loadLib('handlers.php');
        $this->__implement('Telegram_notifier_panteleevHandlers');

        $this->loadCommonExtension();
        $this->loadTemplateCustoms();
    }

    /**
     * Создает вкладки административной панели модуля
     */
    protected function initTabs() {
        $configTabs = $this->getConfigTabs();

        if ($configTabs instanceof iAdminModuleTabs) {
            $configTabs->add("config");
        }
    }

    /**
     * Подключает общие классы функционала
     */
    protected function includeCommonClasses() {
        $this->loadCommonExtension();
        $this->loadTemplateCustoms();
    }

    /**
     * @desc: Получает номер заказа, преобразовывает в сообщение, вызывает corezoid функцию
     * @param: int $orderId номер заказа
     * @param: string $tgMsg сообщение отправляемое в телеграм
     */
    public function renderOrderAndSendMsg($orderId = false, $tgMsg = "Появился новый заказ: %order_id%. Стоимость товара: %price%. Детали по ссылке: %link%") {
        if(!$orderId) return false;

        $collection = umiObjectsCollection::getInstance();
        $orderObject = $collection->getObject($orderId);

        $tgMsgLink = $_SERVER["HTTP_ORIGIN"] . "/admin/emarket/order_edit/" . $orderId . "/";
        $orderName = $orderObject->getValue("number");
        $orderPrice = $orderObject->getValue("total_original_price");

        $tgMsg = str_replace("%order_id%", "$orderName", "$tgMsg");
        $tgMsg = str_replace("%price%", "$orderPrice", "$tgMsg");
        $tgMsg = str_replace("%link%", "$tgMsgLink", "$tgMsg");

        // Call Corezoid API and Send msg to TG
        $this->callCorezoidApi($tgMsg);
    }

    /**
     * @desc: Call Corezoid API thought cURL
     * @link: https://lornajane.net/posts/2011/posting-json-data-with-php-curl
     */
    public function callCorezoidApi($tgMsg = "Новый заказ на вашем сайте.. Не удалось получить детали") {
        $regedit = Service::Registry();

        $tgIsEnableNotification = (bool) $regedit->get('//modules/telegram_notifier_panteleev/tg_enable_notification');
        $tgBotApiKey = $regedit->get('//modules/telegram_notifier_panteleev/tg_bot_api_key');
        $tgChatId = $regedit->get('//modules/telegram_notifier_panteleev/tg_chat_id');
        $czWebhookUrl = $regedit->get('//modules/telegram_notifier_panteleev/cz_webhook_url');

        // Check is enable tg push
        if(!$tgIsEnableNotification
            // or !$tgBotApiKey
            or !$tgChatId
            or !$czWebhookUrl) return false;

        $data = array(
            "chat_id" => "$tgChatId",
            "telegram_token" => "$tgBotApiKey",
            "telegram_massage" => $tgMsg,
        );

        $dataString = json_encode($data);

        $ch = curl_init("$czWebhookUrl");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString))
        );

        curl_exec($ch);

        return "Sent";
    }


};
