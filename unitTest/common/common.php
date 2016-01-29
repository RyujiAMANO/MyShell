<?php

define('PLUGIN_ROOT_DIR', '/var/www/app/app/Plugin/' . getenv('PLUGIN_NAME') . '/');
define('PLUGIN_TEST_DIR', '/var/www/app/app/Plugin/' . getenv('PLUGIN_NAME') . '/Test/Case/');
define('DS', '/');
define('TMP', '/var/www/app/app/tmp/');

require __DIR__ . '/Inflector.php';
require __DIR__ . '/Folder.php';
require __DIR__ . '/File.php';
require dirname(__DIR__) . '/Plugin/Plugin.php';
require dirname(__DIR__) . '/Plugin/Create.php';
require dirname(__DIR__) . '/Plugin/CreateController.php';
require dirname(__DIR__) . '/Plugin/CreateControllerComponent.php';
require dirname(__DIR__) . '/Plugin/CreateModel.php';
require dirname(__DIR__) . '/Plugin/CreateModelBehavior.php';
require dirname(__DIR__) . '/Plugin/CreateOther.php';
//require dirname(__DIR__) . '/Plugin/CreateViewElements.php';
require dirname(__DIR__) . '/Plugin/CreateViewHelper.php';

/**
 * 出力関数
 *
 * @param string $message メッセージ
 * @return void
 */
function output($message) {
	echo $message . chr(10);
}

/**
 * 時間出力関数
 *
 * @param string $message メッセージ
 * @return void
 */
function outputTime() {
	echo date('Y-m-d H:i:s') . chr(10);
}

/**
 * CakeSchemaのダミークラス
 */
class CakeSchema {

}

/**
 * __d()のダミー関数
 */
function __d($plugin, $message) {

}

/**
 * Appのダミークラス
 */
class App {
	public static function uses($plugin, $dir) {

	}
}
