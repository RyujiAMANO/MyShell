<?php
/**
 * 管理プラグインの作成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */


/**
 * Pluginクラス
 */
Class SystemPlugin extends Plugin {

	/**
	 * ロード処理
	 *
	 * @return void
	 */
	public function load() {
		$this->output('[2] 管理プラグイン' . chr(10));

	}
}


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
