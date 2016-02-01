<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4WorkflowControllerEdit extends CreateController4Workflow {

	/**
	 * ワークフローに関するXxxxController::edit()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$argument = $param[1];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'WorkflowControllerEditTest';
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
					'@param string $role ロール',
					'@return array',
				),
				'__data($role = null)',
				array(
					'$frameId = \'6\';',
					'$blockId = \'2\';',
					'$blockKey = \'block_1\';',
					'if ($role === Role::ROOM_ROLE_KEY_GENERAL_USER) {',
					chr(9) . '$contentId = \'3\'; //TODO:idをセットする',
					chr(9) . '$contentKey = \'' . $this->pluginSingularizeUnderscore . '_content_key_2\'; //TODO:keyをセットする',
					'} else {',
					chr(9) . '$contentId = \'2\'; //TODO:idをセットする',
					chr(9) . '$contentKey = \'' . $this->pluginSingularizeUnderscore . '_content_key_1\'; //TODO:keyをセットする',
					'}',
					'',
					'$data = array(',
					chr(9) . '\'save_\' . WorkflowComponent::STATUS_IN_DRAFT => null,',
					chr(9) . '\'Frame\' => array(',
					chr(9) . chr(9) . '\'id\' => $frameId,',
					chr(9) . '),',
					chr(9) . '\'Block\' => array(',
					chr(9) . chr(9) . '\'id\' => $blockId,',
					chr(9) . chr(9) . '\'key\' => $blockKey,',
					chr(9) . chr(9) . '\'language_id\' => \'2\',',
					chr(9) . chr(9) . '\'room_id\' => \'1\',',
					chr(9) . chr(9) . '\'plugin_key\' => $this->plugin,',
					chr(9) . '),',
					'',
					chr(9) . '//TODO:必要のデータセットをここに書く',
					chr(9) . '\'\' => array(',
					chr(9) . chr(9) . '\'id\' => $contentId,',
					chr(9) . chr(9) . '\'key\' => $contentKey,',
					chr(9) . chr(9) . '\'language_id\' => \'2\',',
					chr(9) . chr(9) . '\'status\' => null,',
					chr(9) . '),',
					'',
					chr(9) . '\'WorkflowComment\' => array(',
					chr(9) . chr(9) .  '\'comment\' => \'WorkflowComment save test\',',
					chr(9) . '),',
					');',
					'',
					'return $data;',
				),
				'private'
			) .
			'' . chr(10) .
			$this->_classMethod(
				'editアクションのGETテスト(ログインなし)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderEditGet()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'// * ログインなし',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . $this->pluginSingularizeUnderscore . '_content_key_1\' //TODO:keyをセットする',
					chr(9) . '),',
					chr(9) . '\'assert\' => null, \'exception\' => \'ForbiddenException\'',
					');',
					'',
					'return $results;',
				)
			) .
			'' . chr(10) .
			$this->_classMethod(
				'editアクションのGETテスト(作成権限のみ)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderEditGetByCreatable()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'// * 作成権限のみ',
					'$results = array();',
					'// ** 他人の記事',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . $this->pluginSingularizeUnderscore . '_content_key_1\' //TODO:keyをセットする',
					chr(9) . '),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\'',
					');',
					'// ** 他人の記事(JSON形式)',
					'$results[1] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . $this->pluginSingularizeUnderscore . '_content_key_1\' //TODO:keyをセットする',
					chr(9) . '),',
					chr(9) . '\'assert\' => null, \'exception\' => \'BadRequestException\', \'return\' => \'json\'',
					');',
					'// ** 自分の記事',
					'$results[2] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . $this->pluginSingularizeUnderscore . '_content_key_2\' //TODO:keyをセットする',
					chr(9) . '),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'',
					'return $results;',
				)
			) .
			'' . chr(10) .
			$this->_classMethod(
				'editアクションのGETテスト(編集権限あり、公開権限なし)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderEditGetByEditable()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'// * 編集権限あり',
					'$results = array();',
					'// ** コンテンツあり',
					'$base = 0;',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . $this->pluginSingularizeUnderscore . '_content_key_1\' //TODO:keyをセットする',
					chr(9) . '),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'',
					'// ** コンテンツなし',
					'$results[count($results)] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => \'14\',',
					chr(9) . chr(9) . '\'block_id\' => null,',
					chr(9) . chr(9) . '\'key\' => null',
					chr(9) . '),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertEquals\', \'expected\' => \'emptyRender\'),',
					chr(9) . '\'exception\' => null, \'return\' => \'viewFile\'',
					');',
					'',
					'return $results;',
				)
			) .
			'' . chr(10) .
			$this->_classMethod(
				'editアクションのGETテスト(公開権限あり)用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - assert: テストの期待値' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderEditGetByPublishable()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'// * フレームID指定なしテスト',
					'$results = array();',
					'$results[0] = array(',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => null,',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . $this->pluginSingularizeUnderscore . '_content_key_1\' //TODO:keyをセットする',
					chr(9) . '),',
					chr(9) . '\'assert\' => array(\'method\' => \'assertNotEmpty\'),',
					');',
					'',
					'return $results;',
				)
			) .
			'' . chr(10) .
			$this->_classMethod(
				'editアクションのPOSTテスト用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data: 登録データ' . chr(10) .
					' *  - role: ロール' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - exception: Exception' . chr(10) .
					' *  - return: testActionの実行後の結果',
				array(
					'@return array',
				),
				'dataProviderEditPost()',
				array(
					'$data = $this->__data();',
					'',
					'//テストデータ',
					'$results = array();',
					'// * ログインなし',
					'$contentKey = \'' . $this->pluginSingularizeUnderscore . '_content_key_1\'; //TODO:keyをセットする',
					'array_push($results, array(',
					chr(9) . '\'data\' => $data,',
					chr(9) . '\'role\' => null,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey,',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'ForbiddenException\'',
					'));',
					'// * 作成権限のみ',
					'// ** 他人の記事',
					'$contentKey = \'' . $this->pluginSingularizeUnderscore . '_content_key_1\'; //TODO:keyをセットする',
					'array_push($results, array(',
					chr(9) . '\'data\' => $data,',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_GENERAL_USER,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey,',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'BadRequestException\'',
					'));',
					'$contentKey = \'' . $this->pluginSingularizeUnderscore . '_content_key_1\'; //TODO:keyをセットする',
					'array_push($results, array(',
					chr(9) . '\'data\' => $data,',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_GENERAL_USER,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey,',
					chr(9) . '),',
					chr(9) . '\'exception\' => \'BadRequestException\', \'return\' => \'json\'',
					'));',
					'// ** 自分の記事',
					'$contentKey = \'' . $this->pluginSingularizeUnderscore . '_content_key_2\'; //TODO:keyをセットする',
					'array_push($results, array(',
					chr(9) . '\'data\' => $this->__data(Role::ROOM_ROLE_KEY_GENERAL_USER),',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_GENERAL_USER,',
					chr(9) . '\'urlOptions\' => array(\'',
					chr(9) . chr(9) . 'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey,',
					chr(9) . '),',
					'));',
					'// * 編集権限あり',
					'// ** コンテンツあり',
					'$contentKey = \'' . $this->pluginSingularizeUnderscore . '_content_key_1\'; //TODO:keyをセットする',
					'array_push($results, array(',
					chr(9) . '\'data\' => $data,',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_EDITOR,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey,',
					chr(9) . '),',
					'));',
					'// ** フレームID指定なしテスト',
					'$contentKey = \'' . $this->pluginSingularizeUnderscore . '_content_key_1\'; //TODO:keyをセットする',
					'array_push($results, array(',
					chr(9) . '\'data\' => $data,',
					chr(9) . '\'role\' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => null,',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => $contentKey,',
					chr(9) . '),',
					'));',
					'',
					'return $results;',
				)
			) .
			'' . chr(10) .
			$this->_classMethod(
				'editアクションのValidationErrorテスト用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - data: 登録データ' . chr(10) .
					' *  - urlOptions: URLオプション' . chr(10) .
					' *  - validationError: バリデーションエラー',
				array(
					'@return array',
				),
				'dataProviderEditValidationError()',
				array(
					'$data = $this->__data();',
					'$result = array(',
					chr(9) . '\'data\' => $data,',
					chr(9) . '\'urlOptions\' => array(',
					chr(9) . chr(9) . '\'frame_id\' => $data[\'Frame\'][\'id\'],',
					chr(9) . chr(9) . '\'block_id\' => $data[\'Block\'][\'id\'],',
					chr(9) . chr(9) . '\'key\' => \'' . $this->pluginSingularizeUnderscore . '_content_key_1\', //TODO:keyをセットする',
					chr(9) . '),',
					');',
					'',
					'//テストデータ',
					'$results = array();',
					'array_push($results, Hash::merge($result, array(',
					chr(9) . '\'validationError\' => array(',
					chr(9) . chr(9) . '\'field\' => \'\', //TODO',
					chr(9) . chr(9) . '\'value\' => \'\',',
					chr(9) . chr(9) . '\'message\' => \'\' //TODO:メッセージ追加',
					chr(9) . ')',
					')));',
					'',
					'//TODO:必要なテストデータ追加',
					'',
			)
			) .
			'' . chr(10) .
			$this->_classMethod(
				'view(ctp)ファイルのテスト(公開権限なし)',
				array(
					'@return void',
				),
				'testViewFileByEditable()',
				array(
					'TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_EDITOR);',
					'',
					'//テスト実行',
					'$data = $this->__data();',
					'$this->_testGetAction($data, array(\'method\' => \'assertNotEmpty\'));',
					'',
					'//チェック',
					'$this->assertActionLink(\'delete\', array(\'delete\'), false, $this->view);',
					'$this->assertInput(',
					chr(9) . '\'input\', \'data[Frame][id]\', $data[\'Frame\'][\'id\']',
					');',
					'$this->assertInput(',
					chr(9) . '\'input\', \'data[Block][id]\', $data[\'Block\'][\'id\']',
					');',
					'$this->assertInput(',
					chr(9) . '\'button\', \'save_\' . WorkflowComponent::STATUS_IN_DRAFT, null',
					');',
					'$this->assertInput(',
					chr(9) . '\'button\', \'save_\' . WorkflowComponent::STATUS_APPROVED, null',
					');',
					'',
					'//TODO:上記以外に必要なassert追加',
					'',
					'TestAuthGeneral::logout($this);',
				)
			) .
			'' . chr(10) .
			$this->_classMethod(
				'view(ctp)ファイルのテスト(公開権限あり)',
				array(
					'@return void',
				),
				'testViewFileByPublishable()',
				array(
					'TestAuthGeneral::login($this);',
					'',
					'//テスト実行',
					'$data = $this->__data();',
					'$this->_testGetAction($data, array(\'method\' => \'assertNotEmpty\'));',
					'',
					'//チェック',
					'$this->assertActionLink(\'delete\', array(\'delete\'), false, $this->view);',
					'$this->assertInput(',
					chr(9) . '\'input\', \'data[Frame][id]\', $data[\'Frame\'][\'id\']',
					');',
					'$this->assertInput(',
					chr(9) . '\'input\', \'data[Block][id]\', $data[\'Block\'][\'id\']',
					');',
					'$this->assertInput(',
					chr(9) . '\'button\', \'save_\' . WorkflowComponent::STATUS_IN_DRAFT, null',
					');',
					'$this->assertInput(',
					chr(9) . '\'button\', \'save_\' . WorkflowComponent::STATUS_PUBLISHED, null',
					');',
					'$this->assertInput(',
					chr(9) . '\'input\', \'_method\', \'DELETE\'',
					');',
					'',
					'//TODO:上記以外に必要なassert追加',
					'',
					'TestAuthGeneral::logout($this);',
				)
			) .
			'' . chr(10) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}