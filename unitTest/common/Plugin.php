<?php
/**
 * Pluginクラス
 * 各プラグインの共通クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * CakeSchemaのダミークラス
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
		if (substr($dirName, 0, 1) === '/') {
			$dirName = substr($dirName, 1);
		}
		$dirPath = PLUGIN_ROOT . '/' . $this->plugin . '/' . $dirName;
		$dir = dir($dirPath);

		while (false !== ($fileName = $dir->read())) {
			if (! in_array($fileName, ['.', '..', '.git', 'Schema', 'Migration', 'Test', 'TestSuite'], true) &&
					! is_file($dirPath . '/' . $fileName)) {

				$this->searchFiles($dirName . '/' . $fileName);
			}
			if (in_array(substr($fileName, -3), ['ctp', 'php'], true) &&
					is_file($dirPath . '/' . $fileName)) {

				$this->testFiles[] = array(
					'dir' => $dirName,
					'file' => $fileName,
					'path' => $dirPath . '/' . $fileName,
					'extension' => substr($fileName, -3),
					'type' => $this->_getTestType($dirName),
				);
			}
		}
	}

	/**
	 * タイプの取得
	 *
	 * @param string $dirName ディレクトリ名
	 * @return string|null テストType文字列
	 */
	protected function _getTestType($dirName) {
		$types = [
			'Model/Behavior',
			'Controller/Component',
			'View/Helper',
			'View/Elements',
			'Model',
			'Controller',
			'View',
		];
		foreach ($types as $type) {
			if (substr($dirName, 0, strlen($type)) === $type) {
				return $type;
			}
		}
		return null;
	}

	/**
	 * クラス名の取得
	 *
	 * @param array $testFile テストファイルデータ配列
	 * @return string|bool Class名文字列
	 */
	protected function getClassName($testFile) {
		return substr($testFile['file'], 0, -1 * strlen($testFile['extension']));
	}

}

