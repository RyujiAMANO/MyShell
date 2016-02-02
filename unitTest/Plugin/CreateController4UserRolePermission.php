<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4UserRolePermission extends CreateController4 {

	/**
	 * XxxxAppControllerのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest() {
		$testControllerName = 'Test' . $this->testFile['class'];

		$this->_createTestController($testControllerName);
		$this->_createTestView($testControllerName);

		$this->_create($testControllerName);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createTestController($testControllerName) {
		$this->createTestPluginDir('Controller');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'' . $this->testFile['class'] . '\', \'' . $this->plugin . '.Controller\')',
				),
				'アクセス権限(Permission)テスト用Controller'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\' . $this->plugin . '\\Controller',
				'アクセス権限(Permission)テスト用Controller'
			) .
			'class ' . $testControllerName . 'Controller extends ' . $this->testFile['class'] . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classMethod(
				'index',
				array(
					'@return void',
				),
				'index()',
				array(
					'$this->autoRender = true;'
				)
			) .
			'}' .
			'' . chr(10) .
			'';
		$this->createTestPluginFile('Controller/' . $testControllerName . 'PermissionController.php', $output);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createTestView($testControllerName) {
		$this->createTestPluginDir('View/' . $testControllerName . 'Permission');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(),
				'アクセス権限(Permission)テスト用Viewファイル'
			) .
			'?>' . chr(10) .
			'' . chr(10) .
			$this->testFile['dir'] . '/' . $this->testFile['file'] . 'Permission' . chr(10) .
			'';
		$this->createTestPluginFile('View/' . $testControllerName . 'Permission/index.ctp', $output);
	}

	/**
	 * Controller/Component::xxxxx()のテストコード生成
	 *
	 * @param string $testControllerName Testコントローラ名
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _create($testControllerName) {
		$testSuiteTest = 'NetCommonsControllerTestCase';
		$testSuitePlugin = 'NetCommons';
		$className = $this->testFile['class'] . 'PermissionTest';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '.TestSuite\')',
					'App::uses(\'UserRole\', \'UserRoles.Model\')',
				),
				'アクセス権限(Permission)のテスト'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Controller\\' . Inflector::camelize(ucfirst($this->testFile['file'])),
				'アクセス権限(Permission)のテスト'
			) .
			'class ' . $className . ' extends NetCommonsControllerTestCase {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
					'Fixtures',
					'array',
					'public',
					'fixtures',
					array(
						'array();',
					)
			) .
			$this->_classVariablePlugin() .
			$this->_classMethod(
				'setUp method',
				array(
					'@return void',
				),
				'setUp()',
				array(
					'parent::setUp();',
					'',
					'//テストプラグインのロード',
					'NetCommonsCakeTestCase::loadTestPlugin($this, \'' . $this->plugin . '\', \'Test' . $this->plugin . '\');',
				)
			) .
			$this->_classMethod(
				'tearDown method',
				array(
					'@return void',
				),
				'tearDown()',
				array(
					'//ログアウト',
					'TestAuthGeneral::logout($this);',
					'',
					'parent::tearDown();',
				)
			) .
			$this->_classMethod(
				'アクセスチェック用DataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' . chr(10) .
					' *  - role 会員権限、nullはログインなし' . chr(10) .
					' *  - exception Exception文字列',
				array(
					'@return array',
				),
				'dataProvider()',
				array(
					'$results = array();',
					'',
					'//テストデータ',
					'// * ログインなし',
					'$results[0] = array(\'role\' => null, \'exception\' => \'ForbiddenException\');',
					'// * 一般権限',
					'$results[1] = array(\'role\' => UserRole::USER_ROLE_KEY_COMMON_USER, \'exception\' => \'ForbiddenException\');',
					'// * サイト権限',
					'$results[2] = array(\'role\' => UserRole::USER_ROLE_KEY_ADMINISTRATOR, \'exception\' => false);',
					'// * システム権限',
					'$results[3] = array(\'role\' => UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR, \'exception\' => false);',
					'',
					'return $results;',
				)
			) .
			$this->_classMethod(
				'アクセスチェック',
				array(
					'@param string|null $role 会員権限、nullはログインなし',
					'@param string $exception Exception文字列',
					'@dataProvider dataProvider',
					'@return void',
				),
				'testPermission($role, $exception)',
				array(
					'if (isset($role)) {',
					chr(9) . 'TestAuthGeneral::login($this, $role);',
					'}',
					'if ($exception) {',
					chr(9) . '$this->setExpectedException($exception);',
					'}',
					'',
					'//テスト実行',
					'$this->_testNcAction(\'/' .
						Inflector::underscore('Test' . $this->plugin) . '/' .
						Inflector::underscore($testControllerName) . '/index\', array(',
					chr(9) . '\'method\' => \'get\'',
					'));',
					'',
					'if (! $exception) {',
					chr(9) . '$this->assertNotEmpty($this->view);',
					'}',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile('PermissionTest.php', $output);
		$this->deleteFile('empty');
	}

}