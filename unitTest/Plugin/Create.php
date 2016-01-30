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
	public function __construct($testFile = null, $isCreate = true) {
		$this->testFile = $testFile;

		$this->plugin = getenv('PLUGIN_NAME');
		$this->pluginType = getenv('PLUGIN_TYPE');
		$this->authorName = getenv('AUTHOR_NAME');
		$this->authorEmail = getenv('AUTHOR_EMAIL');

		if (file_exists(PLUGIN_ROOT_DIR . 'Config/Schema/schema.php')) {
			require_once PLUGIN_ROOT_DIR . 'Config/Schema/schema.php';

			if (class_exists($this->plugin . 'Schema')) {
				$class = $this->plugin . 'Schema';
				$Schema = new $class;

				$this->schemas = get_class_vars(get_class($Schema));
				unset($this->schemas['connection']);
			}
		}

		//$isCreate = false;
		if (! $isCreate) {
			return;
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
				output('ディレクトリの作成に失敗しました。');
				output(sprintf('(%s)', $createPath) . chr(10));
				exit(1);
			} else {
				output('ディレクトリを作成しました。');
				output(sprintf('(%s)', $createPath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * テストディレクトリの作成
	 *
	 * @return bool
	 */
	public function createTestPluginDir($dirName) {
		$createPath = PLUGIN_TEST_PLUGIN . $dirName;
		if (! file_exists($createPath)) {
			$result = (new Folder())->create($createPath);
			if (! $result) {
				output('ディレクトリの作成に失敗しました。');
				output(sprintf('(%s)', $createPath) . chr(10));
				exit(1);
			} else {
				output('ディレクトリを作成しました。');
				output(sprintf('(%s)', $createPath) . chr(10));
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
	public function createFile($fileName, $output = '') {
		$filePath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file'])) . '/' . $fileName;

		if ($fileName !== 'empty' && file_exists($filePath)) {
			if (getenv('EXISTING_FILE') === 'y') {
				$this->deleteFile($fileName);
			} elseif (getenv('EXISTING_FILE') === 'c') {
				output('ファイルが既に存在します。上書きしますか。');
				output(sprintf('(%s)', $filePath));
				echo '(y)es|(n)o> ';
				if (trim(fgets(STDIN)) === 'y') {
					$this->deleteFile($fileName);
				}
			}
		}

		if (! file_exists($filePath)) {
			$result = (new File($filePath))->write($output);
			if (! $result) {
				output('ファイルの作成に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを作成しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
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
	public function createTestPluginFile($fileName, $output = '') {
		$filePath = PLUGIN_TEST_PLUGIN . $fileName;
		if ($fileName !== 'empty' && file_exists($filePath)) {
			if (getenv('EXISTING_FILE') === 'y') {
				$this->deleteTestPluginFile($fileName);
			} elseif (getenv('EXISTING_FILE') === 'c') {
				output('ファイルが既に存在します。上書きしますか。');
				output(sprintf('(%s)', $filePath));
				echo '(y)es|(n)o> ';
				if (trim(fgets(STDIN)) === 'y') {
					$this->deleteTestPluginFile($fileName);
				}
			}
		}

		if (! file_exists($filePath)) {
			$result = (new File($filePath))->write($output);
			if (! $result) {
				output('ファイルの作成に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを作成しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * ファイルの削除
	 *
	 * @param string $fileName ファイル名
	 * @return bool
	 */
	public function deleteFile($fileName) {
		$filePath = PLUGIN_TEST_DIR . $this->testFile['dir'] . '/' . Inflector::camelize(ucfirst($this->testFile['file'])) . '/' . $fileName;
		if (file_exists($filePath)) {
			$result = (new File($filePath))->delete();
			if (! $result) {
				output('ファイルの削除に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを削除しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
			}
			return $result;
		}
		return true;
	}

	/**
	 * ファイルの削除
	 *
	 * @param string $fileName ファイル名
	 * @return bool
	 */
	public function deleteTestPluginFile($fileName) {
		$filePath = PLUGIN_TEST_PLUGIN . $fileName;
		if (file_exists($filePath)) {
			$result = (new File($filePath))->delete();
			if (! $result) {
				output('ファイルの削除に失敗しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
				exit(1);
			} else {
				output('ファイルを削除しました。');
				output(sprintf('(%s)', $filePath) . chr(10));
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
		if (preg_match_all('/function ([_a-zA-Z0-9]+)?\((.*)?\)/', $file, $matches)) {
			foreach (array_keys($matches[0]) as $i) {
				$result[$i] = array($matches[1][$i], $matches[2][$i], $this->getFunctionComment($matches[1][$i]));
			}
		}

		return $result;
	}

	/**
	 * メソッドのコメント取得
	 *
	 * @return array メソッドリスト
	 */
	public function getFunctionComment($function) {
		if (! file_exists($this->testFile['path'])) {
			return true;
		}

		$file = file_get_contents($this->testFile['path']);
		$fileAsArray = explode(chr(10), $file);

		$result = '';
		$funcCheck = false;
		foreach ($fileAsArray as $line) {
			if ($funcCheck) {
				if (preg_match('/^(.+)?' . $function . '(.+)/', $line)) {
					break;
				}
				$funcCheck = false;
			}

			if ($line === '/**') {
				$result = $line;
			} else {
				$result .= chr(10) . $line;
			}
			if ($line === ' */') {
				$funcCheck = true;
			} else {
				$funcCheck = false;
			}
		}

		return $result;
	}

	/**
	 * Phpdoc FileHeader
	 *
	 * @return string
	 */
	protected function _phpdocFileHeader($function, $appUses = array(), $headerDescription = null) {
		if (! $headerDescription) {
			if (getenv('USE_DEFAULT_COMMENT') !== 'y') {
				output('ファイルの説明文の入力して下さい。');
				output('[' . $this->testFile['class'] . '::' . $function . '()のテスト' . ']');
				echo '> ';
				$headerDescription = trim(fgets(STDIN));
			} else {
				$headerDescription = null;
			}
			if (! $headerDescription) {
				$headerDescription = $this->testFile['class'] . '::' . $function . '()のテスト';
			}
		}

		return
			'/**' . chr(10) .
			' * ' . $headerDescription . chr(10) .
			' *' . chr(10) .
			' * @author Noriko Arai <arai@nii.ac.jp>' . chr(10) .
			' * @author ' . $this->authorName . ' <' . $this->authorEmail . '>' . chr(10) .
			' * @link http://www.netcommons.org NetCommons Project' . chr(10) .
			' * @license http://www.netcommons.org/license.txt NetCommons License' . chr(10) .
			' * @copyright Copyright 2014, NetCommons Project' . chr(10) .
			' */' . chr(10) .
			'' . chr(10) .
			implode(';' .chr(10), $appUses) . ';' .chr(10) .
			'' . chr(10) .
			'';
	}

	/**
	 * Phpdoc ClassHeader
	 *
	 * @return string
	 */
	protected function _phpdocClassHeader($function, $package, $headerDescription = null) {
		if (! $headerDescription) {
			if (getenv('USE_DEFAULT_COMMENT') !== 'y') {
				output('クラスの説明文の入力して下さい。');
				output('[' . $this->testFile['class'] . '::' . $function . '()のテスト' . ']');
				echo '> ';
				$headerDescription = trim(fgets(STDIN));
			} else {
				$headerDescription = null;
			}
			if (! $headerDescription) {
				$headerDescription = $this->testFile['class'] . '::' . $function . '()のテスト';
			}
		}

		return
			'/**' . chr(10) .
			' * ' . $headerDescription . chr(10) .
			' *' . chr(10) .
			' * @author ' . $this->authorName . ' <' . $this->authorEmail . '>' . chr(10) .
			' * @package ' . $package . chr(10) .
			' */' . chr(10) .
			'';
	}

	/**
	 * Phpdoc ClassVariable
	 *
	 * @return string
	 */
	protected function _classVariable($phpdoc, $type, $scope, $variable, $values) {
		if (! $phpdoc) {
			output(sprintf('%s変数の説明の入力して下さい。', $variable));
			echo '> ';
			$phpdoc = trim(fgets(STDIN));
		}

		$outputValue = '';
		foreach ($values as $value) {
			if (! $outputValue) {
				$outputValue .= $value . chr(10);
			} else {
				$outputValue .= chr(9) . $value . chr(10);
			}
		}

		return
			'/**' . chr(10) .
			' * ' . $phpdoc . chr(10) .
			' *' . chr(10) .
			' * @var ' . $type . chr(10) .
			' */' . chr(10) .
			'' . chr(9) . $scope . ' $' . $variable . ' = ' . $outputValue.
			'' . chr(10) .
			'';
	}

	/**
	 * Fixtures
	 *
	 * @return string
	 */
	public function _classVariableFixtures() {
		$values = array(
			'array('
		);
		foreach (array_keys($this->schemas) as $fixture) {
			$values[] = chr(9) . '\'plugin.' . Inflector::underscore($this->plugin) . '.' . Inflector::singularize($fixture) . '\',';
		}
		$values[] = ');';

		return
			$this->_classVariable(
				'Fixtures',
				'array',
				'public',
				'fixtures',
				$values
			);
	}

	/**
	 * _classVariablePlugin
	 *
	 * @return string
	 */
	public function _classVariablePlugin() {
		return
			$this->_classVariable(
					'Plugin name',
					'string',
					'public',
					'plugin',
					array(
						'\'' . Inflector::underscore($this->plugin). '\';',
					)
			);
	}

	/**
	 * Phpdoc ClassVariable
	 *
	 * @return string
	 */
	protected function _classMethod($phpdoc, $params, $method, $processes) {
		if (! $phpdoc) {
			output(sprintf('%sメソッドの説明の入力して下さい。', $method));
			echo '> ';
			$phpdoc = trim(fgets(STDIN));
		}

		$outputProcess = '';
		foreach ($processes as $process) {
			if ($process) {
				$outputProcess .= chr(9) . chr(9) . $process . chr(10);
			} else {
				$outputProcess .= chr(10);
			}
		}

		$outputParams = '';
		foreach ($params as $value) {
			$outputParams .= ' * ' . $value . chr(10);
		}

		return
			'/**' . chr(10) .
			' * ' . $phpdoc . chr(10) .
			' *' . chr(10) .
			$outputParams .
			' */' . chr(10) .
			'' . chr(9) . 'public function ' . $method . ' {' . chr(10) .
			'' . $outputProcess .
			'' . chr(9) . '}' . chr(10) .
			'';
	}

}

