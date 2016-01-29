<?php
/**
 * Createする共通クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Createする共通クラス
 */
Class CreateObject {

	/**
	 * ファイルデータ
	 */
	public $testFile = null;

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
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		$this->testFile = $testFile;

		$this->plugin = getenv('PLUGIN_NAME');
		$this->pluginType = getenv('PLUGIN_TYPE');
		$this->authorName = getenv('AUTHOR_NAME');
		$this->authorEmail = getenv('AUTHOR_EMAIL');

		if (file_exists(PLUGIN_ROOT_DIR . 'Config/Schema/schema.php')) {
			require PLUGIN_ROOT_DIR . 'Config/Schema/schema.php';

			if (class_exists($this->plugin . 'Schema')) {
				$class = $this->plugin . 'Schema';
				$Schema = new $class;

				$this->schemas = get_class_vars(get_class($Schema));
				unset($this->schemas['connection']);
			}
		}

		if (! $this->createTestDir()) {
			return false;
		}
		if (! $this->createFile('empty')) {
			return false;
		}
	}

	/**
	 * テストディレクトリの作成
	 *
	 * @return bool
	 */
	public function createTestDir() {
		$createPath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file']));
		if (! file_exists($createPath)) {
			$result = (new Folder())->create($createPath);
			if (! $result) {
				output(sprintf('%sディレクトリの作成に失敗しました。', $createPath));
				exit(1);
			} else {
				output(sprintf('%sディレクトリの作成しました。', $createPath));
			}
			return $result;
		}
		return true;
	}

	/**
	 * ファイルの作成
	 *
	 * @param string $fileName ファイル名
	 * @return bool
	 */
	public function createFile($fileName) {
		$createPath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file'])) . '/' . $fileName;
		if (! file_exists($createPath)) {
			$result = (new File($createPath))->write('');
			if (! $result) {
				output(sprintf('%sファイルの作成に失敗しました。', -1 * (strlen(PLUGIN_ROOT_DIR) + 2)));
				exit(1);
			} else {
				output(sprintf('%sファイルの作成しました。', substr($createPath, -1 * (strlen(PLUGIN_ROOT_DIR) + 2))));
			}
			return $result;
		}
		return true;
	}

	/**
	 * メソッド一覧の取得
	 *
	 * @return array メソッドリスト
	 */
	public function getFunctions() {
		if (! file_exists($this->testFile['path'])) {
			return true;
		}

		$file = file_get_contents($this->testFile['path']);
		$matches = array();
		$result = array();
		if (preg_match_all('/function ([_a-zA-Z0-9]+)?\(.*/', $file, $matches)) {
			$result = $matches[1];
		}

		return $result;
	}

	/**
	 * Phpdoc FileHeader
	 *
	 * @return string
	 */
	public function _phpdocFileHeader($headerDescription, $testSuitePlugin, $testSuiteTest) {
		return
			'/**' . chr(10) .
			' * ' . $headerDescription . chr(10) .
			' * ' . chr(10) .
			' * @author Noriko Arai <arai@nii.ac.jp>' . chr(10) .
			' * @author ' . $this->authorName . ' <' . $this->authorEmail . '>' . chr(10) .
			' * @link http://www.netcommons.org NetCommons Project' . chr(10) .
			' * @license http://www.netcommons.org/license.txt NetCommons License' . chr(10) .
			' * @copyright Copyright 2014, NetCommons Project' . chr(10) .
			' */' . chr(10) .
			'' . chr(10) .
			'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuit\');' . chr(10) .
			'' . chr(10) .
			'';
	}

	/**
	 * Phpdoc ClassHeader
	 *
	 * @return string
	 */
	public function _phpdocClassHeader($headerDescription) {
		$package = 'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . preg_quote(Inflector::camelize(ucfirst($this->testFile['file'])));

		return
			'/**' . chr(10) .
			' * ' . $headerDescription . chr(10) .
			' *' . chr(10) .
			' * @author ' . $this->authorName . ' <' . $this->authorEmail . '>' . chr(10) .
			' * @package ' . $package . chr(10) .
			'*/' . chr(10) .
			'';
	}

}

