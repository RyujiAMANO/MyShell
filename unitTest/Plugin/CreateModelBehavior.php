<?php
/**
 * ModelBehaviorのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * ModelBehaviorのテストファイル生成クラス
 */
Class CreateModelBehavior extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## ModelBehaviorのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * ModelBehaviorのテストコード生成
	 *
	 * @return void
	 */
	public function create() {
		$testModelName = 'Test' . $this->testFile['class'] . 'Model';

		$this->_createTestModel($testModelName);
		$functions = $this->getFunctions();

		foreach ($functions as $param) {
			if (substr($param[0], 0, strlen('setup')) === 'setup') {
				continue;
			}

			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			$this->_create($testModelName, $param);
		}
	}

	/**
	 * Phpdoc ClassVariable
	 *
	 * @return string
	 */
	protected function _createTestModel($testModelName) {
		$this->createTestPluginDir('Model');

		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				'',
				array(
					'App::uses(\'AppModel\', \'Model\')',
				),
				$this->testFile['class'] . 'テスト用Model'
			) .
			$this->_phpdocClassHeader(
				'',
				'NetCommons\\' . $this->plugin . '\\Test\\test_app\\Plugin\\' . $this->plugin . '\\Model',
				$this->testFile['class'] . 'テスト用Model'
			) .
			'class ' . $testModelName . ' extends AppModel {' . chr(10) .
			'' . chr(10) .
			$this->_classVariable(
					'テーブル名',
					'mixed',
					'public',
					'useTable',
					array('false;')
			) .
			$this->_classVariable(
					'使用ビヘイビア',
					'array',
					'public',
					'actsAs',
					array(
						'array(',
						chr(9) . '\'' .  $this->plugin . '.' . substr($this->testFile['class'], 0, -1 * strlen('Behavior')) . '\'',
						');',
					)
			) .
			'}' .
			'' . chr(10) .
			'';
		$this->createTestPluginFile('Model/' . $testModelName . '.php', $output);
	}

	/**
	 * ModelBehavior::xxxxx()のテストコード生成
	 *
	 * @param string $testModelName Testモデル名
	 * @param array $param メソッドデータ配列
	 * @return void
	 */
	protected function _create($testModelName, $param) {
		$function = $param[0];
		$argument = $param[1];
		$matches = array();
		$commentParam = array();
		if (preg_match_all('/' . preg_quote(' * ', '/') . '(@param [^Model].*)' . '/', $param[2], $matches)) {
			$commentParam = $matches[1];
		}
		$dataProviderComment = '';
		foreach ($commentParam as $i => $comment) {
			$dataProviderComment .=  chr(10) . ' *  - ' . preg_replace('/^@param (.*)?\$/', '', $comment);
		}

		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//メソッドの内容
		$processes1 = array();
		$processes2 = array();

		$processes1[] = '//TODO:テストパタンを書く';
		$processes1[] = '$result[0] = array();';
		$arguments = explode(', ', $argument);
		$methodArg = '';
		foreach ($arguments as $arg) {
			$matches = array();
			if (preg_match('/^\$([_0-9a-zA-Z]+)( \= )?(.*)/', $arg, $matches)) {
				$processes1[] = '$result[0][\'' . $matches[1] . '\'] = ' . ($matches[3] ? $matches[3] : 'null') . ';';
				$methodArg .= ', $' . $matches[1];
			}
		}
		$processes1[] = '';
		$processes1[] = 'return $result;';

		$processes2[] = '//テスト実施';
		$processes2[] = '$result = $this->TestModel->' . $function . '(' . substr($methodArg, 2) . ');';
		$processes2[] = '';
		$processes2[] = '//チェック';
		$processes2[] = '//TODO:Assertを書く';
		$processes2[] = 'debug($result);';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'NetCommonsCakeTestCase\', \'NetCommons.TestSuite\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\Model\\Behavior\\' . Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends NetCommonsCakeTestCase {' . chr(10) .
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
			$this->_classMethod(
				'setUp method',
				array(
					'@return void',
				),
				'setUp()',
				array(
					'parent::setUp();',
					'NetCommonsCakeTestCase::loadTestPlugin($this, \'' . $this->plugin . '\', \'Test' . $this->plugin . '\');',
					'$this->TestModel = ClassRegistry::init(\'Test' . $this->plugin . '.' . $testModelName . '\');',
				)
			) .
			$this->_classMethod(
				$function . '()テストのDataProvider' . chr(10) .
					' *' . chr(10) .
					' * ### 戻り値' .
					$dataProviderComment,
				array(
					'@return array データ',
				),
				'dataProvider()',
				$processes1
			) .
			$this->_classMethod(
				$function . '()のテスト',
				array_merge($commentParam, array(
					'@dataProvider dataProvider',
					'@return void',
				)),
				'test' . ucfirst($function) . '(' .  substr($methodArg, 2) .')',
				$processes2
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

