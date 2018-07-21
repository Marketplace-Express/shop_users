<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Shop_users\Models' => APP_PATH . '/common/models/',
    'Shop_users'        => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'Shop_users\Modules\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'Shop_users\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();
