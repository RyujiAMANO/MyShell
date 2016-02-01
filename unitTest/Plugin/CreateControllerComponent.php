<?php
/**
 * Controller/Componentのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controller/Componentのテストファイル生成クラス
 */
Class CreateControllerComponent extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## Controller/Componentのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
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
					'App::uses(\'AppController\', \'Controller\')',
				),
				$this->testFile['class'] . 'テスト用Controller'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\' . $this->plugin . '\\Controller',
				$this->testFile['class'] . 'テスト用Controller'
			) .
			'class ' . $testControllerName . 'Controller extends AppController {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
					'使用コンポーネント',
					'array',
					'public',
					'components',
					array(
						'array(',
						chr(9) . '\'' .  $this->plugin . '.' . substr($this->testFile['class'], 0, -1 * strlen('Component')) . '\'',
						');',
					)
			) .
			$this->_classMethod(
				'index',
				array(
					'@return void',
				),
				'index()',
				array()
			) .
			'}' .
			'' . chr(10) .
			'';
		$this->createTestPluginFile('Controller/' . $testControllerName . 'Controller.php', $output);
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return string
	 */
	protected function _createTestView($testControllerName) {
		$this->createTestPluginDir('View/' . $testControllerName);

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(),
				$this->testFile['class'] . 'テスト用Viewファイル'
			) .
			'?>' . chr(10) .
			'' . chr(10) .
			$this->testFile['dir'] . '/' . $this->testFile['file'] . chr(10) .
			'';
		$this->createTestPluginFile('View/' . $testControllerName . '/index.ctp', $output);
	}

	/**
	 * Controller/Componentのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$testControllerName = 'Test' . $this->testFile['class'];

		$this->_createTestController($testControllerName);
		$this->_createTestView($testControllerName);

		$functions = $this->getFunctions();

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			$this->_create($testControllerName, $param);
		}
	}

	/**
	 * Controller/Component::xxxxx()のテストコード生成
	 *
	 * @param string $testControllerName Testコントローラ名
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _create($testControllerName, $param) {
		$function = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'NetCommonsControllerTestCase\', \'NetCommons.TestSuite\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Controller\\Component\\' . Inflector::camelize(ucfirst($this->testFile['file']))
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
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				array(
					'//テストコントローラ生成',
					'$this->generateNc(\'Test' . $this->plugin . '.' . $testControllerName . '\');',
					'',
					'//ログイン',
					'TestAuthGeneral::login($this);',
					'',
					'//テスト実行',
					'$this->_testNcAction(\'/' .
						Inflector::underscore('Test' . $this->plugin) . '/' .
						Inflector::underscore($testControllerName) . '/index\', array(',
					chr(9) . '\'method\' => \'get\'',
					'));',
					'',
					'//チェック',
					'$pattern = \'/\' . preg_quote(\'' . $this->testFile['dir'] . '/' . $this->testFile['file'] . '\', \'/\') . \'/\';',
					'$this->assertRegExp($pattern, $this->view);',
					'',
					'//TODO:必要に応じてassert追加する',
					'',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

