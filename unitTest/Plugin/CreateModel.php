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
		output(sprintf('## Modelのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
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

		foreach ($functions as $func) {
			$function = $func[0];
			$argument = $func[1];
			output(chr(10) . sprintf('#### テストファイル生成  %s(%s)', $function, $argument) . chr(10));

			if (substr($function, 0, strlen('get')) === 'get') {
				$this->_createGet($function, $argument);

			} elseif (substr($function, 0, strlen('save')) === 'save') {
				$this->_createSave($function, $argument);

			} elseif (substr($function, 0, strlen('delete')) === 'delete') {
				$this->_createDelete($function, $argument);

			} elseif (substr($function, 0, strlen('beforeValidate')) === 'beforeValidate' ||
					substr($function, 0, strlen('validate')) === 'validate') {
				$this->_createValidate('validate');

			} else {
				output(sprintf('%sは、どのタイプのテストですか。', $function . '()'));
				output('[0 or 省略] 下記以外');
				output('[1] Get');
				output('[2] Save');
				output('[3] Delete');
				output('[4] Validate');
				echo '> ';
				$input = trim(fgets(STDIN));
				if ($input === '1') {
					$this->_createGet($function, $argument);
				}  elseif ($input === '2') {
					$this->_createSave($function, $argument);
				}  elseif ($input === '3') {
					$this->_createDelete($function, $argument);
				}  elseif ($input === '4') {
					$this->_createValidate('validate');
				} else {
					$this->_createOther($function, $argument);
				}
			}

			output(chr(10) . '----------' . chr(10));
		}
	}

	/**
	 * Modelのメンバ変数
	 *
	 * @param string $function メソッド名
	 * @return string
	 */
	private function __getClassVariable($function) {
		return
			$this->_classVariableFixtures() .
			$this->_classVariablePlugin() .
			$this->_classVariable(
					'Model name',
					'string',
					'protected',
					'_modelName',
					array(
						'\'' . $this->testFile['class']. '\';',
					)
			) .
			$this->_classVariable(
					'Method name',
					'string',
					'protected',
					'_methodName',
					array(
						'\'' . $function. '\';',
					)
			);
	}

	/**
	 * Model::getXxxx()のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createGet($function, $argument) {
		if ($this->isWorkflow()) {
			$testSuitePlugin = 'Workflow';
			$testSuiteTest = 'WorkflowGetTest';
		} else {
			$testSuitePlugin = 'NetCommons';
			$testSuiteTest = 'NetCommonsGetTest';
		}
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//メソッドの内容
		$processes = array();
		$processes[] = '//データ生成';
		$arguments = explode(', ', $argument);
		$methodArg = '';
		foreach ($arguments as $arg) {
			$matches = array();
			if (preg_match('/([\$_0-9a-zA-Z]+)?( \= )?(.*)/', $arg, $matches)) {
				$processes[] = $matches[1] . ' = ' . ($matches[3] ? $matches[3] : 'null') . ';';
				$methodArg .= ', ' . $matches[1];
			}
		}
		$processes[] = '';
		$processes[] = '//テスト実施';
		$processes[] = '$model = $this->_modelName;';
		$processes[] = '$methodName = $this->_methodName;';
		$processes[] = '$result = $this->$model->$methodName(' . substr($methodArg, 2) . ');';
		$processes[] = '';
		$processes[] = '//チェック';
		$processes[] = '//TODO:Assertを書く';
		$processes[] = 'debug($result);';

		//出力文字列
		$this->_createOther($function, $argument, $testSuiteTest, $testSuitePlugin);
	}

	/**
	 * Model::saveXxxx()のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createSave($function, $argument) {
		if ($this->isWorkflow()) {
			$testSuitePlugin = 'Workflow';
			$testSuiteTest = 'WorkflowSaveTest';
		} else {
			$testSuitePlugin = 'NetCommons';
			$testSuiteTest = 'NetCommonsSaveTest';
		}
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//メソッドの内容
		$processes1 = array();
		$processes1[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes1[] = '';
		$processes1[] = '//TODO:saveパタンを書く';
		$processes1[] = '$result = array();';
		$processes1[] = '$result[0] = array($data);';
		$processes1[] = '';
		$processes1[] = 'return $result;';

		$processes2 = array();
		$processes2[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes2[] = '';
		$processes2[] = '//TODO:saveパタンを書く';
		$processes2[] = 'return array(';
		$processes2[] = chr(9) . 'array($data, \'' . Inflector::camelize(ucfirst($this->plugin)) . '.' . $this->testFile['class'] . '\', \'save\'),';
		$processes2[] = ');';

		$processes3 = array();
		$processes3[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes3[] = '';
		$processes3[] = '//TODO:saveパタンを書く';
		$processes3[] = 'return array(';
		$processes3[] = chr(9) . 'array($data, \'' . Inflector::camelize(ucfirst($this->plugin)) . '.' . $this->testFile['class'] . '\'),';
		$processes3[] = ');';

		//出力文字列
		$output =
				'<?php' . chr(10) .
				$this->_phpdocFileHeader($function, array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . Inflector::camelize(ucfirst($this->plugin)) . '.Test/Fixture\')',
				)) .
				$this->_phpdocClassHeader($function) .
				'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
				'' . chr(10) .
				$this->__getClassVariable($function) .
				$this->_classMethod(
					'Save用DataProvider' . chr(10) .
						' *' . chr(10) .
						' * ### 戻り値' . chr(10) .
						' *  - data 登録データ',
					'array テストデータ',
					'dataProviderSave',
					$processes1
				) .
				'' . chr(10) .
				$this->_classMethod(
					'SaveのExceptionError用DataProvider' . chr(10) .
						' *' . chr(10) .
						' * ### 戻り値' . chr(10) .
						' *  - data 登録データ' . chr(10) .
						' *  - mockModel Mockのモデル' . chr(10) .
						' *  - mockMethod Mockのメソッド',
					'array テストデータ',
					'dataProviderSaveOnExceptionError',
					$processes2
				) .
				'' . chr(10) .
				$this->_classMethod(
					'SaveのValidationError用DataProvider' . chr(10) .
						' *' . chr(10) .
						' * ### 戻り値' . chr(10) .
						' *  - data 登録データ' . chr(10) .
						' *  - mockModel Mockのモデル',
					'array テストデータ',
					'dataProviderSaveOnValidationError',
					$processes3
				) .
				'' . chr(10) .
				'}' .
				'' . chr(10) .
				'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

	/**
	 * Model::deleteXxxx()のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createDelete($function, $argument) {
		if ($this->isWorkflow()) {
			$testSuitePlugin = 'Workflow';
			$testSuiteTest = 'WorkflowDeleteTest';
		} else {
			$testSuitePlugin = 'NetCommons';
			$testSuiteTest = 'NetCommonsDeleteTest';
		}
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//メソッドの内容
		$processes1 = array();
		$processes1[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes1[] = '$association = array();';
		$processes1[] = '';
		$processes1[] = '//TODO:deleteパタンを書く';
		$processes1[] = '$result = array();';
		$processes1[] = '$result[0] = array($data, $association);';
		$processes1[] = '';
		$processes1[] = 'return $result;';

		$processes2 = array();
		$processes2[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes2[] = '';
		$processes2[] = '//TODO:deleteパタンを書く';
		$processes2[] = 'return array(';
		$processes2[] = chr(9) . 'array($data, \'' . Inflector::camelize(ucfirst($this->plugin)) . '.' . $this->testFile['class'] . '\', \'deleteAll\'),';
		$processes2[] = ');';

		//出力文字列
		$output =
				'<?php' . chr(10) .
				$this->_phpdocFileHeader($function, array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . Inflector::camelize(ucfirst($this->plugin)) . '.Test/Fixture\')',
				)) .
				$this->_phpdocClassHeader($function) .
				'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
				'' . chr(10) .
				$this->__getClassVariable($function) .
				$this->_classMethod(
					'Delete用DataProvider' . chr(10) .
						' *' . chr(10) .
						' * ### 戻り値' . chr(10) .
						' *  - data: 削除データ' . chr(10) .
						' *  - associationModels: 削除確認の関連モデル array(model => conditions)',
					'array テストデータ',
					'dataProviderDelete',
					$processes1
				) .
				'' . chr(10) .
				$this->_classMethod(
					'ExceptionError用DataProvider' . chr(10) .
						' *' . chr(10) .
						' * ### 戻り値' . chr(10) .
						' *  - data 登録データ' . chr(10) .
						' *  - mockModel Mockのモデル' . chr(10) .
						' *  - mockMethod Mockのメソッド',
					'array テストデータ',
					'dataProviderDeleteOnExceptionError',
					$processes2
				) .
				'' . chr(10) .
				'}' .
				'' . chr(10) .
				'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

	/**
	 * Model::validates()のテストコード生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createValidate($function) {
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//メソッドの内容
		$processes = array();
		$processes[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes[] = '';
		$processes[] = '//TODO:validateパタンを書く';
		$processes[] = 'return array(';
		$processes[] = ');';

		//出力文字列
		$output =
				'<?php' . chr(10) .
				$this->_phpdocFileHeader($function, array(
					'App::uses(\'NetCommonsValidateTest\', \'NetCommons.TestSuite\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . Inflector::camelize(ucfirst($this->plugin)) . '.Test/Fixture\')',
				)) .
				$this->_phpdocClassHeader($function) .
				'class ' . $className . ' extends NetCommonsValidateTest {' . chr(10) .
				'' . chr(10) .
				$this->__getClassVariable($function) .
				$this->_classMethod(
					'ValidationErrorのDataProvider' . chr(10) .
						' *' . chr(10) .
						' * ### 戻り値' . chr(10) .
						' *  - field フィールド名' . chr(10) .
						' *  - value セットする値' . chr(10) .
						' *  - message エラーメッセージ' . chr(10) .
						' *  - overwrite 上書きするデータ',
					'array テストデータ',
					'dataProviderValidationError',
					$processes
				) .
				'' . chr(10) .
				'}' .
				'' . chr(10) .
				'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

	/**
	 * テストファイル生成
	 *
	 * @param string $function メソッド名
	 * @return void
	 */
	protected function _createOther($function, $argument, $testSuiteTest = 'NetCommonsModelTestCase', $testSuitePlugin = 'NetCommons') {
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//メソッドの内容
		$processes = array();
		$processes[] = '//データ生成';
		$arguments = explode(', ', $argument);
		$methodArg = '';
		foreach ($arguments as $arg) {
			$matches = array();
			if (preg_match('/([\$_0-9a-zA-Z]+)?( \= )?(.*)/', $arg, $matches)) {
				$processes[] = $matches[1] . ' = ' . ($matches[3] ? $matches[3] : 'null') . ';';
				$methodArg .= ', ' . $matches[1];
			}
		}
		$processes[] = '';
		$processes[] = '//テスト実施';
		$processes[] = '$model = $this->_modelName;';
		$processes[] = '$methodName = $this->_methodName;';
		$processes[] = '$result = $this->$model->$methodName(' . substr($methodArg, 2) . ');';
		$processes[] = '';
		$processes[] = '//チェック';
		$processes[] = '//TODO:Assertを書く';
		$processes[] = 'debug($result);';

		//出力文字列
		$output =
				'<?php' . chr(10) .
				$this->_phpdocFileHeader($function, array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')'
				)) .
				$this->_phpdocClassHeader($function) .
				'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
				'' . chr(10) .
				$this->__getClassVariable($function) .
				$this->_classMethod(
					$function . '()のテスト',
					'void',
					'test' . ucfirst($function),
					$processes
				) .
				'' . chr(10) .
				'}' .
				'' . chr(10) .
				'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

