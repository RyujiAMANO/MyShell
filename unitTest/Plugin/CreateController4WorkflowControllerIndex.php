<?php
/**
 * ワークフローに関するXxxxController::index()のテストコード生成
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * ワークフローに関するXxxxController::index()のテストコード生成
 */
Class CreateController4WorkflowControllerIndex extends CreateController4Workflow {

	/**
	 * ワークフローに関するXxxxController::index()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'WorkflowControllerIndexTest';
		$testSuitePlugin = 'Workflow';

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
				'テストDataの取得',
				array(
					'@return array',
				),
				'__data()',
				array(
					'$frameId = \'6\';',
					'$blockId = \'2\';',
					'',
					'$data = array(',
					chr(9) . '\'Frame\' => array(',
					chr(9) . chr(9) . '\'id\' => $frameId,',
					chr(9) . '),',
					chr(9) . '\'Block\' => array(',
					chr(9) . chr(9) . '\'id\' => $blockId,',
					chr(9) . '),',
					');',
					'',
					'return $data;',
				),
				'private'
			) .
			$this->_classMethod(
				'indexアクションのテスト(ログインなし)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderIndex()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(\'frame_id\' => $data[\'Frame\'][\'id\']),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'',
					'//TODO:必要なテストデータ追加',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'indexアクションのテスト(編集権限あり)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderIndexByEditable()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(\'frame_id\' => $data[\'Frame\'][\'id\']),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'',
					'//TODO:必要なテストデータ追加',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'indexアクションのテスト(作成権限のみ)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderIndexByCreatable()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(\'frame_id\' => $data[\'Frame\'][\'id\']),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'',
					'//TODO:必要なテストデータ追加',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'view(ctp)ファイルのテスト' . chr(10),
				array(
					'@return void',
				),
				'testViewFile()',
				array(
					'TestAuthGeneral::login($this);',
					'',
					'//テスト実行',
					'$data = $this->__data();',
					'$this->_testGetAction($data, array(\'method\' => \'assertNotEmpty\'));',
					'',
					'//チェック',
					'//TODO:view(ctp)ファイルに対するassert追加',
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