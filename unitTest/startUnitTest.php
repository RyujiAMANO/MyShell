<?php
/**
 * UnitTest用ファイル作成ソース
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

error_reporting(E_ALL);

require __DIR__ . '/common/common.php';
require __DIR__ . '/common/Inflector.php';
require __DIR__ . '/common/Plugin.php';
require __DIR__ . '/SystemPlugin/SystemPlugin.php';

if (getenv('PLUGIN_TYPE') == '1') {
	//[1] 一般プラグイン

} elseif (getenv('PLUGIN_TYPE') == '2') {
	//[2] 管理プラグイン
	(new SystemPlugin())->load();

} elseif (getenv('PLUGIN_TYPE') == '3') {
	//[3] コアプラグイン

}

//Class SystemPlugin {
//	public plugin = null;
//
//	public function __constract() {
//		$this->plugin =
//	}
//
//	function load() {
//
//	}
//}
//
//
//function load($dirName, $indent, $displayUrl) {
//	global $plugin, $messages, $fileCount, $displayChildren;
//
//	$dir = dir(PLUGIN_DIR . $dirName);
//
//	$outputs = array();
//	if ($dirName === $plugin) {
//		$shellFileName = $dirName . '.html';
//	} else {
//		$shellFileName = strtr(substr($dirName, strlen($plugin) + 1), '/', '_') . '.html';
//	}
//	if ($displayChildren || $dirName === $plugin) {
//		exec('php ' . __DIR__ . '/parse_caverage.php ' . $plugin . ' ' . $shellFileName . ' ' . $indent . ' ' . (int)$displayUrl, $outputs);
//		if ($outputs) {
//			if ($dirName !== $plugin) {
//				$messages .= "\n";
//			}
//			foreach ($outputs as $output) {
//				$messages .= $output . "\n";
//			}
//		}
//	}
//
//	while (false !== ($fileName = $dir->read())) {
//		if (! in_array($fileName, ['.', '..', '.git', 'Schema', 'Migration', 'Test', 'TestSuite'], true) &&
//				! is_file(PLUGIN_DIR . $dirName . '/' . $fileName)) {
//
//			output_caverage($dirName . '/' . $fileName, $indent + 4, false);
//		}
//		if (in_array(substr($fileName, -3), ['ctp', 'php'], true) &&
//				is_file(PLUGIN_DIR . $dirName . '/' . $fileName)) {
//
//			$fileCount++;
//		}
//	}
//	$dir->close();
//}
//
//output_caverage($plugin, 0, true);
