<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4OtherController extends CreateController4 {

	/**
	 * XxxxControllerのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param atring $action アクション
	 * @return bool 成功・失敗
	 */
	public function createTest($param, $action = null) {
		$function = $param[0];
		if (! $action) {
			$action = $function;
		}
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'NetCommonsControllerTestCase';
		$testSuitePlugin = 'NetCommons';

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
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Controller\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_getClassVariable($function) .
			$this->_classMethod(
				$action . '()アクションのテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				array(
					'TestAuthGeneral::login($this);',
					'',
					'//TODO:テストデータ',
					'',
					'//テスト実行',
					'$this->_testNcAction(',
					chr(9) . '\'/\' . $this->plugin . \'/\' . $this->_controller . \'/' . $action . '\',',
					chr(9) . 'array(\'method\' => \'get\')',
					');',
					'',
					'//チェック',
					'//TODO:assert追加',
					'debug($this->view);',
					'',
					'TestAuthGeneral::logout($this);',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}