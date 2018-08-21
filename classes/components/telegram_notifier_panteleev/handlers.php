<?php

use UmiCms\Service;

/** Класс, содержащий обработчики событий */
class Telegram_notifier_panteleevHandlers {

    /** @var events $module */
    public $module;

    /**
     * @desk: Обработчик события создания заказа модуля "Интернет-магазин"
     * @param iUmiEventPoint $event событие создания заказа
     */
    public function onEmarketOrderAdd(iUmiEventPoint $event) {
        if ($event->getMode() == 'after' && $event->getParam('old-status-id') != $event->getParam('new-status-id')) {
            if ($event->getParam('new-status-id') == order::getStatusByCode('waiting') && $event->getParam('old-status-id') != order::getStatusByCode('editing')) {
                $moduleEmarket = cmsController::getInstance()->getModule('emarket');
                $moduleTg = cmsController::getInstance()->getModule('telegram_notifier_panteleev');

                /** @var iUmiObject $order */
                $order = $event->getRef('order');

                // Get order id
                $orderId = $order->getId();

                // Call TG send msg
                $moduleTg->renderOrderAndSendMsg($orderId);
            }
        }
    }


}
