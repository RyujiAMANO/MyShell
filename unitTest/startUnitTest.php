<?php
/**
 * UnitTest用ファイル作成ソース
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

error_reporting(E_ALL);

require __DIR__ . '/common/common.php';
require __DIR__ . '/SystemPlugin/SystemPlugin.php';

if (getenv('PLUGIN_TYPE') == '1') {
	//[1] 一般プラグイン

} elseif (getenv('PLUGIN_TYPE') == '2') {
	//[2] 管理プラグイン
	(new SystemPlugin())->load();

} elseif (getenv('PLUGIN_TYPE') == '3') {
	//[3] コアプラグイン

}
