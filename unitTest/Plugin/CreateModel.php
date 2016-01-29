<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(sprintf('Modelのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * Workflowのモデルかどうかチェック
	 *
	 * @return bool
	 */
	public function isWorkflow() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/Workflow\.Workflow/', $file);
	}

	/**
	 * Modelのテストコード生成
	 *
	 * @return void
	 */
	public function create() {
		$functions = $this->getFunctions();

		foreach ($functions as $function) {
			var_dump(substr($function, 0, strlen('get')));
			if (substr($function, 0, strlen('get')) === 'get') {
				$this->_createGet($function);

			} elseif (substr($function, 0, strlen('save')) === 'save') {
				$this->_createSave($function);

			} elseif (substr($function, 0, strlen('delete')) === 'delete') {
				$this->_createDelete($function);

			} elseif (substr($function, 0, strlen('beforeValidate')) === 'beforeValidate' ||
					substr($function, 0, strlen('validate')) === 'validate') {
				$this->_createValidate($function);

			} else {
				output(sprintf('%sは、どのタイプのテストですか。', $function . '()'));
				output('[0 or 省略] 下記以外');
				output('[1] Get');
				output('[2] Save');
				output('[3] Delete');
				output('[4] Validate');
				echo '> ';
				$input = fgets(STDIN);
				if ($input === '1') {
					$this->_createGet($function);
				}  elseif ($input === '2') {
					$this->_createSave($function);
				}  elseif ($input === '3') {
					$this->_createDelete($function);
				}  elseif ($input === '4') {
					$this->_createValidate($function);
				} else {
					$this->_createOther($function);
				}
			}
		}
	}

	/**
	 * Model::getXxxx()のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createGet($function) {
		if ($this->isWorkflow()) {
			$testSuitePlugin = 'Workflow';
			$testSuiteTest = 'WorkflowSaveTest';
		} else {
			$testSuitePlugin = 'NetCommons';
			$testSuiteTest = 'NetCommonsSaveTest';
		}

		$package = 'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . preg_quote(Inflector::camelize(ucfirst($this->testFile['file'])));
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($this->testFile['file']));

		output('ファイルの説明文の入力して下さい。');
		output('[' . $this->testFile['class'] . '::' . $function . '()のテスト' . ']');
		echo '> ';
		$fileHeaderDescription = fgets(STDIN);
		if (! $fileHeaderDescription) {
			$fileHeaderDescription = '[' . $this->testFile['class'] . '::' . $function . '()のテスト' . ']';
		}
		output('クラスの説明文の入力して下さい。');
		output('[' . $this->testFile['class'] . '::' . $function . '()のテスト' . ']');
		echo '> ';
		$classHeaderDescription = fgets(STDIN);
		if (! $classHeaderDescription) {
			$classHeaderDescription = '[' . $this->testFile['class'] . '::' . $function . '()のテスト' . ']';
		}

		$output =
				$this->_phpdocFileHeader($fileHeaderDescription, $testSuitePlugin, $testSuiteTest) .
				$this->_phpdocClassHeader($classHeaderDescription);
				'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
						
				'}';

//		$test =
//			'/**' . chr(10) .
//			' * ' . $this->testFile['class'] . '::' . $function . '()のテスト' . chr(10) .
//			' * ' . chr(10) .
//			' * @author Noriko Arai <arai@nii.ac.jp>' . chr(10) .
//			' * @author ' . $this->authorName . ' <' . $this->authorEmail . '>' . chr(10) .
//			' * @link http://www.netcommons.org NetCommons Project' . chr(10) .
//			' * @license http://www.netcommons.org/license.txt NetCommons License' . chr(10) .
//			' * @copyright Copyright 2014, NetCommons Project' . chr(10) .
//			' */' . chr(10) .
//			'' . chr(10) .
//			'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuit\');' . chr(10) .
//			'' . chr(10) .
//			'/**' . chr(10) .
//			' * ' . $this->testFile['class'] . '::' . $function . '()のテスト' . chr(10) .
//			' *' . chr(10) .
//			' * @author ' . $this->authorName . ' <' . $this->authorEmail . '>' . chr(10) .
//			' * @package ' . $package . chr(10) .
//			'*/' . chr(10) .
//			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
//			'' . chr(10) .
//			'/**' . chr(10) .
//			' * Plugin name' . chr(10) .
//			' *' . chr(10) .
//			' * @var string' . chr(10) .
//			' */' . chr(10) .
//			'' . chr(9) . 'public $plugin = \'' . Inflector::underscore($this->plugin). '\';' . chr(10) .
//			'' . chr(10) .
//			'/**' . chr(10) .
//			' * Fixtures' . chr(10) .
//			' *' . chr(10) .
//			' * @var array' . chr(10) .
//			' */' . chr(10) .
//			'' . chr(9) . 'public $fixtures = array();' . chr(10) .
//			'' . chr(9) . ')' . chr(10) .
//			'' . chr(10) .
//			'/**' . chr(10) .
//			' * Model name' . chr(10) .
//			' *' . chr(10) .
//			' * @var array' . chr(10) .
//			' */' . chr(10) .
//			'' . chr(9) . 'protected $_modelName = \'' . $this->testFile['class'] . '\';' . chr(10) .
//			'' . chr(10) .
//			'/**' . chr(10) .
//			' * Method name' . chr(10) .
//			' *' . chr(10) .
//			' * @var array' . chr(10) .
//			' */' . chr(10) .
//			'' . chr(9) . 'protected $_methodName = \'' . $function . '\';' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'' . chr(10) .
//			'}';
	}

	/**
	 * Model::saveXxxx()のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createSave($function) {
	}

	/**
	 * Model::deleteXxxx()のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createDelete($function) {
	}

	/**
	 * Model::validates()のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createValidate($function) {
	}

	/**
	 * Model::getXxxx()、Model::saveXxxx()、Model::deleteXxxx()、Model::validates()以外のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createOther($function) {
	}

}

