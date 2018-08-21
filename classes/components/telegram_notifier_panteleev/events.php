<?php

use UmiCms\Service;

// Проверяет активировано ли в модуле функция отправки сообщения в телеграм
if (Service::Registry()->get('//modules/telegram_notifier_panteleev/tg_enable_notification')) {
    new umiEventListener('order-status-changed', 'telegram_notifier_panteleev', 'onEmarketOrderAdd');
}

