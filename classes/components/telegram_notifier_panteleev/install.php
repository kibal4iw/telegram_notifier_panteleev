<?php
/**
 * Created by AlexPanteleev.
 * Email: alex@panteleev.com.ua
 * Date: 19.08.2018
 * Time: 7:38
 */

/**
 * @var array $INFO реестр модуля
 */
$INFO = [
    'name' => 'telegram_notifier_panteleev', // Имя модуля
    'config' => '1', // У модуля есть настройки
    'default_method' => 'config', // Метод по умолчанию в клиентской части
    'default_method_admin' => 'config', // Метод по умолчанию в административной части
    'func_perms' => 'Группы прав на функционал модуля', // Группы прав
    'func_perms/guest' => 'Гостевые права', // Гостевая группа прав
    'func_perms/admin' => 'Административные права', // Административная группа прав
    //'paging/' => 'Настройки постраничного вывода', // Группа настроек
    //'paging/pages' => 25, // Настройка количества выводимых страниц
    //'paging/objects' => 25, // Настройка количества выводимых объектов
];

/**
 * @var array $COMPONENTS файлы модуля
 */
$COMPONENTS = [
    './classes/components/telegram_notifier_panteleev/admin.php',
    './classes/components/telegram_notifier_panteleev/class.php',
    './classes/components/telegram_notifier_panteleev/customAdmin.php',
    './classes/components/telegram_notifier_panteleev/customMacros.php',
    './classes/components/telegram_notifier_panteleev/events.php',
    './classes/components/telegram_notifier_panteleev/handlers.php',
    './classes/components/telegram_notifier_panteleev/i18n.php',
    './classes/components/telegram_notifier_panteleev/install.php',
    './classes/components/telegram_notifier_panteleev/lang.php',
    './classes/components/telegram_notifier_panteleev/macros.php',
    './classes/components/telegram_notifier_panteleev/permissions.php',
];