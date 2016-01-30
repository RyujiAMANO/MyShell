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
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
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

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if (substr($param[0], 0, strlen('get')) === 'get') {
				$this->_createGet($param);

			} elseif (substr($param[0], 0, strlen('save')) === 'save') {
				$this->_createSave($param);

			} elseif (substr($param[0], 0, strlen('delete')) === 'delete') {
				$this->_createDelete($param);

			} elseif (substr($param[0], 0, strlen('update')) === 'update' ||
					substr($param[0], 0, strlen('beforeSave')) === 'beforeSave') {
				$this->_createOther($param);

			} elseif (substr($param[0], 0, strlen('beforeValidate')) === 'beforeValidate' ||
					substr($param[0], 0, strlen('validate')) === 'validate') {
				$this->_createValidate(array('validate', ''));

			} else {
				output(sprintf('%sは、どのタイプのテストですか。', $param[0] . '()'));
				output('[0 or 省略] 下記以外');
				output('[1] Get');
				output('[2] Save');
				output('[3] Delete');
				output('[4] Validate');
				echo '> ';
				$input = trim(fgets(STDIN));
				if ($input === '1') {
					$this->_createGet($param);
				}  elseif ($input === '2') {
					$this->_createSave($param);
				}  elseif ($input === '3') {
					$this->_createDelete($param);
				}  elseif ($input === '4') {
					$this->_createValidate(array('validate', ''));
				} else {
					$this->_createOther($param);
				}
			}
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
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _createGet($param) {
		$function = $param[0];
		$argument = $param[1];

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
		$this->_createOther($param, $testSuiteTest, $testSuitePlugin);
	}

	/**
	 * Model::saveXxxx()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _createSave($param) {
		$function = $param[0];
		$argument = $param[1];

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
		$processes1[] = '//TODO:テストパタンを書く';
		$processes1[] = '$result = array();';
		$processes1[] = '$result[0] = array($data);';
		$processes1[] = '';
		$processes1[] = 'return $result;';

		$processes2 = array();
		$processes2[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes2[] = '';
		$processes2[] = '//TODO:テストパタンを書く';
		$processes2[] = 'return array(';
		$processes2[] = chr(9) . 'array($data, \'' . $this->plugin . '.' . $this->testFile['class'] . '\', \'save\'),';
		$processes2[] = ');';

		$processes3 = array();
		$processes3[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes3[] = '';
		$processes3[] = '//TODO:テストパタンを書く';
		$processes3[] = 'return array(';
		$processes3[] = chr(9) . 'array($data, \'' . $this->plugin . '.' . $this->testFile['class'] . '\'),';
		$processes3[] = ');';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . $this->plugin . '.Test/Fixture\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->__getClassVariable($function) .
			$this->_classMethod(
				'Save用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data 登録データ',
				array(
					'@return array テストデータ',
				),
				'dataProviderSave()',
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
				array(
					'@return array テストデータ',
				),
				'dataProviderSaveOnExceptionError()',
				$processes2
			) .
			'' . chr(10) .
			$this->_classMethod(
				'SaveのValidationError用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data 登録データ' . chr(10) .
					' *  - mockModel Mockのモデル',
				array(
					'@return array テストデータ',
				),
				'dataProviderSaveOnValidationError()',
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
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _createDelete($param) {
		$function = $param[0];
		$argument = $param[1];

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
		$processes1[] = '//TODO:テストパタンを書く';
		$processes1[] = '$result = array();';
		$processes1[] = '$result[0] = array($data, $association);';
		$processes1[] = '';
		$processes1[] = 'return $result;';

		$processes2 = array();
		$processes2[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes2[] = '';
		$processes2[] = '//TODO:テストパタンを書く';
		$processes2[] = 'return array(';
		$processes2[] = chr(9) . 'array($data, \'' . $this->plugin . '.' . $this->testFile['class'] . '\', \'deleteAll\'),';
		$processes2[] = ');';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . $this->plugin . '.Test/Fixture\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->__getClassVariable($function) .
			$this->_classMethod(
				'Delete用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data: 削除データ' . chr(10) .
					' *  - associationModels: 削除確認の関連モデル array(model => conditions)',
				array(
					'@return array テストデータ',
				),
				'dataProviderDelete()',
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
				array(
					'@return array テストデータ',
				),
				'dataProviderDeleteOnExceptionError()',
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
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _createValidate($param) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//メソッドの内容
		$processes = array();
		$processes[] = '$data[\'' . $this->testFile['class'] . '\'] = (new ' . $this->testFile['class'] . 'Fixture())->records[0];';
		$processes[] = '';
		$processes[] = '//TODO:テストパタンを書く';
		$processes[] = 'return array(';
		$processes[] = ');';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'NetCommonsValidateTest\', \'NetCommons.TestSuite\')',
					'App::uses(\'' . $this->testFile['class'] . 'Fixture\', \'' . $this->plugin . '.Test/Fixture\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
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
				array(
					'@return array テストデータ',
				),
				'dataProviderValidationError()',
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
	 * @param array $param メソッドデータ配列
	 * @param string $testSuiteTest テストSuite
	 * @param string $testSuitePlugin プラグイン
	 * @return void
	 */
	protected function _createOther($param, $testSuiteTest = 'NetCommonsModelTestCase', $testSuitePlugin = 'NetCommons') {
		$function = $param[0];
		$argument = $param[1];

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
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->__getClassVariable($function) .
			$this->_classMethod(
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
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

