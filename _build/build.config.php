<?php

/* define package */
define('PKG_NAME', 'lmims');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));

define('PKG_VERSION', '0.0.2');
define('PKG_RELEASE', 'beta');
define('PKG_AUTO_INSTALL', true);
define('PKG_DEV', true);

if (PKG_DEV) {
  define('PKG_NAMESPACE_PATH', '{base_path}'. PKG_NAME .'/core/components/'. PKG_NAME_LOWER .'/');
  define('PKG_NAMESPACE_ASSETS_PATH', '{base_path}'. PKG_NAME .'/assets/components/'. PKG_NAME_LOWER .'/');
} else {
  define('PKG_NAMESPACE_PATH', '{core_path}components/'. PKG_NAME_LOWER .'/');
  define('PKG_NAMESPACE_ASSETS_PATH', '{assets_path}components/'. PKG_NAME_LOWER .'/');
}

/* define paths */
if (isset($_SERVER['MODX_BASE_PATH'])) {
	define('MODX_BASE_PATH', $_SERVER['MODX_BASE_PATH']);
} else if (file_exists(dirname(dirname(dirname(__FILE__))) . '/index.php')) {
	define('MODX_BASE_PATH', dirname(dirname(dirname(__FILE__))) . '/');
} else {
	die("Couldn't find modx's base path!");
}
define('MODX_BASE_URL', '/');

if (file_exists(MODX_BASE_PATH . 'core')) {
  define('MODX_CORE_PATH', MODX_BASE_PATH . 'core/');
} else if (file_exists(dirname(MODX_BASE_PATH) . '/core')) {
  define('MODX_CORE_PATH', dirname(MODX_BASE_PATH) . '/core/');
} else {
  die("Couldn't find modx's core path!");
}


if (file_exists(MODX_BASE_PATH . '/manager')) {
  define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
  define('MODX_MANAGER_URL', MODX_BASE_URL . 'manager/');
} else if (file_exists(MODX_BASE_PATH . '/adminka')) {
  define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'adminka/');
  define('MODX_MANAGER_URL', MODX_BASE_URL . 'adminka/');
} else {
  die("Couldn't find modx's manager path!");
}

if (file_exists(MODX_BASE_PATH . 'connectors-7rXt-B1s')) {
  define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors-7rXt-B1s/');
  define('MODX_CONNECTORS_URL', MODX_BASE_URL . 'connectors-7rXt-B1s/');
} else {
  die("Couldn't find modx's connectors path!");
}

if (file_exists(MODX_BASE_PATH . '/assets')) {
  define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');
  define('MODX_ASSETS_URL', MODX_BASE_URL . 'assets/');
} else {
  die("Couldn't find modx's assets path!");
}

/* define build options */
//define('BUILD_MENU_UPDATE', false);
//define('BUILD_ACTION_UPDATE', false);
//define('BUILD_EVENT_UPDATE', false);
//define('BUILD_POLICY_UPDATE', false);
//define('BUILD_POLICY_TEMPLATE_UPDATE', false);
//define('BUILD_PERMISSION_UPDATE', false);

define('BUILD_SETTING_UPDATE', PKG_DEV);
//define('BUILD_CHUNK_UPDATE', true);
//define('BUILD_SNIPPET_UPDATE', true);
define('BUILD_PLUGIN_UPDATE', true);
//define('BUILD_TEMPLATE_UPDATE', true);

//define('BUILD_CHUNK_STATIC', PKG_DEV);
//define('BUILD_SNIPPET_STATIC', PKG_DEV);
define('BUILD_PLUGIN_STATIC', PKG_DEV);
//define('BUILD_TEMPLATE_STATIC', PKG_DEV);

$BUILD_RESOLVERS = array(
	'tables',
);