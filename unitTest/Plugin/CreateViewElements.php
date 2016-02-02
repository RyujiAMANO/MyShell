<?php
/**
 * View/Elementsのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * View/Elementsのテストファイル生成クラス
 */
Class CreateViewElements extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## View/Elementsのテストコード生成(%s)', $testFile['dir']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * View/Elementsのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$testControllerName = 'Test' . $this->plugin . $this->testFile['class'];

		foreach ($this->testFile['files'] as $i => $file) {
			if ($this->isBlockRolePermission($file)) {
				unset($this->testFile['files'][$i]);
				continue;
			}
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s', $file['dir'] . '/' . $file['file']) . chr(10));

			$this->_create($testControllerName, array($file['file']));
		}

		$this->_createTestController($testControllerName);
		$this->_createTestView($testControllerName);
	}

	/**
	 * Workflowのモデルかどうかチェック
	 *
	 * @return bool
	 */
	public function isBlockRolePermission($file) {
		if (! file_exists($file['path'])) {
			return false;
		}

		$result = file_get_contents($file['path']);
		if (preg_match('/' . preg_quote('element(\'Blocks.block_creatable_setting\'', '/') . '/', $result) &&
				preg_match('/' . preg_quote('element(\'Blocks.block_approval_setting\'', '/') . '/', $result)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * テストプラグインの生成
	 *
	 * @return void
	 */
	protected function _createTestController($testControllerName) {
		if (! $this->testFile['files']) {
			return;
		}
		$this->createTestPluginDir('Controller');

		$classMethods = '';
		foreach ($this->testFile['files'] as $file) {
			$classMethods .= $this->_classMethod(
				$file['file'],
				array(
					'@return void',
				),
				$file['file'] . '()',
				array(
					'$this->autoRender = true;'
				)
			);
		}

		$authAllows = Hash::extract($this->testFile['files'], '{n}.file');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'AppController\', \'Controller\')',
				),
				$this->testFile['dir'] . '/' . $file['file'] . 'テスト用Controller'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\' . $this->plugin . '\\Controller',
				$this->testFile['dir'] . '/' . $file['file'] . 'テスト用Controller'
			) .
			'class ' . $testControllerName . 'Controller extends AppController {' . chr(10) .
			'' . chr(10) .
			$this->_classMethod(
				'beforeRender',
				array(
					'@return void',
				),
				'beforeRender()',
				array(
					'parent::beforeFilter();',
					'$this->Auth->allow(\'' . implode('\', \'', $authAllows) . '\');',
				)
			) .
			$classMethods .
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
		if (! $this->testFile['files']) {
			return;
		}
		$this->createTestPluginDir('View/' . $testControllerName);

		foreach ($this->testFile['files'] as $file) {
			if ($file['dir'] === 'View/Elements') {
				$element = '$this->element(\'' . $this->plugin . '.' . $file['file'] . '\');';
			} else {
				$element = '$this->element(\'' .
					$this->plugin . '.' . substr($file['dir'], strlen('View/Elements') + 1) . '/' . $file['file'] .
				'\');';
			}

			$output =
				'<?php' . chr(10) .
				$this->_phpdocFileHeader(
					'',
					array(),
					$this->testFile['dir'] . '/' . $file['file'] . 'テスト用Viewファイル'
				) .
				'?>' . chr(10) .
				'' . chr(10) .
				$this->testFile['dir'] . '/' . $file['file'] . chr(10) .
				'' . chr(10) .
				'<?php echo ' . $element . chr(10) .
				'';
			$this->createTestPluginFile('View/' . $testControllerName . '/' . $file['file'] . '.ctp', $output);
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
		$element = $param[0];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($element)) . 'Test';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$element,
				array(
					'App::uses(\'NetCommonsControllerTestCase\', \'NetCommons.TestSuite\')',
				),
				$this->testFile['dir'] . '/' . $element . 'のテスト'
			) .
			$this->_phpdocClassHeader(
				$element,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' . strtr($this->testFile['dir'], '/', '\\') . '\\' . Inflector::camelize(ucfirst($element)),
				$this->testFile['dir'] . '/' . $element . 'のテスト'
			) .
			'class ' . $this->plugin . $className . ' extends NetCommonsControllerTestCase {' . chr(10) .
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
				$this->testFile['dir'] . '/' . $element . 'のテスト',
				array(
					'@return void',
				),
				'test' . Inflector::camelize(ucfirst($element)) . '()',
				array(
					'//テストコントローラ生成',
					'$this->generateNc(\'Test' . $this->plugin . '.' . $testControllerName . '\');',
					'',
					'//テスト実行',
					'$this->_testNcAction(\'/' .
						Inflector::underscore('Test' . $this->plugin) . '/' .
						Inflector::underscore($testControllerName) . '/' . $element . '\', array(',
					chr(9) . '\'method\' => \'get\'',
					'));',
					'',
					'//チェック',
					'$pattern = \'/\' . preg_quote(\'' . $this->testFile['dir'] . '/' . $element . '\', \'/\') . \'/\';',
					'$this->assertRegExp($pattern, $this->view);',
					'',
					'//TODO:必要に応じてassert追加する',
					'debug($this->view);',
				)
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($element)) . 'Test.php', $output);
		$this->deleteFile('empty');
		$this->deleteFile(PLUGIN_TEST_DIR . 'View/Elements/empty');
	}

}

