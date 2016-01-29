<?php
/**
 * Pluginクラス
 * 各プラグインの共通クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

class CakeSchema {

}

/**
 * Pluginクラス
 */
Class Plugin {

	/**
	 * プラグイン名
	 */
	public $plugin = null;

	/**
	 * プラグインの種類
	 */
	public $pluginType = null;

	/**
	 * スキーマ
	 */
	public $schemas = array();

	/**
	 * テスト対象のファイル保管配列
	 */
	public $testFiles = array();

	/**
	 * コンストラクター
	 */
	public function __construct() {
		$this->output('##################################');
		$this->output(' UnitTest用ファイル作成開始します ');
		$this->output('##################################');

		$this->plugin = getenv('PLUGIN_NAME');
		$this->pluginType = getenv('PLUGIN_TYPE');
		$this->authorName = getenv('AUTHOR_NAME');
		$this->authorEmail = getenv('AUTHOR_EMAIL');

		if (file_exists(PLUGIN_ROOT . '/' . $this->plugin . '/Config/Schema/schema.php')) {
			require PLUGIN_ROOT . '/' . $this->plugin . '/Config/Schema/schema.php';

			if (class_exists($this->plugin . 'Schema')) {
				$class = $this->plugin . 'Schema';
				$Schema = new $class;

				$this->schemas = get_class_vars(get_class($Schema));
				unset($this->schemas['connection']);
			}
		}

		$this->searchFiles('');
	}

	/**
	 * デストラクター.
	 */
	function __destruct() {
		$this->output(chr(10));
		$this->output('UnitTest用ファイル作成が終了しました ');
		$this->output('-------------------------------------------------' . chr(10));
	}

	/**
	 * ロード処理
	 *
	 * @return void
	 */
	function load() {}

	/**
	 * 出力
	 *
	 * @param string $message メッセージ
	 * @return void
	 */
	public function output($message) {
		echo $message . chr(10);
	}

	/**
	 * 出力
	 *
	 * @param string $message メッセージ
	 * @return void
	 */
	public function outputTime() {
		echo date('Y-m-d H:i:s') . chr(10);
	}

	/**
	 * ファイルのサーチ
	 *
	 * @param string $dirName ディレクトリ名
	 * @return void
	 */
	public function searchFiles($dirName) {
		$dir = dir(PLUGIN_ROOT . $dirName);

		while (false !== ($fileName = $dir->read())) {
			if (! in_array($fileName, ['.', '..', '.git', 'Schema', 'Migration', 'Test', 'TestSuite'], true) &&
					! is_file(PLUGIN_ROOT . '/' . $dirName . '/' . $fileName)) {

				$this->searchFiles($dirName . '/' . $fileName);
			}
			if (in_array(substr($fileName, -3), ['ctp', 'php'], true) &&
					is_file(PLUGIN_ROOT . '/' . $dirName . '/' . $fileName)) {

				$this->testFiles[] = array(
					'file' => $dirName . '/' . $fileName,
					'path' => PLUGIN_ROOT . '/' . $dirName . '/' . $fileName,
					'extension' => substr($fileName, -3),
				);
			}
		}
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
