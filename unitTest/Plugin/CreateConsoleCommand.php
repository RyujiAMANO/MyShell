<?php
/**
 * Console/Commandのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Console/Commandのテストファイル生成クラス
 */
Class CreateConsoleCommand extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## Console/Commandのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * Console/Commandのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions();

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			$testSuitePlugin = 'NetCommons.TestSuite';
			$testSuiteTest = 'NetCommonsConsoleTestCase';

			$this->_create($param, $testSuitePlugin, $testSuiteTest);
		}
	}

	/**
	 * Consoleのテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @param string $testSuitePlugin テストSuiteのプラグイン
	 * @param string $testSuiteTest テストSuite名
	 * @return void
	 */
	protected function _create($param, $testSuitePlugin, $testSuiteTest) {
		$function = $param[0];
		$argument = $param[1];

		$className = $this->plugin . Inflector::camelize(strtr($this->testFile['dir'], '/', '_')) .
						$this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';

		//出力文字列
		$output =
			'<?php' . chr(10) .
			$this->_phpdocFileHeader(
				$function,
				array(
					'App::uses(\'' . $testSuiteTest . '\', \'' . $testSuitePlugin . '\')',
				)
			) .
			$this->_phpdocClassHeader(
				$function,
				'NetCommons\\' . $this->plugin . '\\Test\\Case\\' .
						str_replace('/', '\\', $this->testFile['dir']) . '\\' .
						Inflector::camelize(ucfirst($this->testFile['file']))
			) .
			'class ' . $className . ' extends ' . $testSuiteTest . ' {' . chr(10) .
			'' . chr(10) .
			$this->_classVariablePlugin();

		$output .=
			$this->_classVariable(
					'Shell name',
					'string',
					'protected',
					'_shellName',
					array(
						'\'' . $this->testFile['class'] . '\';',
					)
			);

		$arguments = explode(', ', $argument);
		$processes = array(
			'$shell = $this->_shellName;',
			'$this->loadShell($shell);',
			'',
			'//チェック',
			'$this->$shell->expects($this->at(0))->method(\'out\')',
			chr(9) . '->with(\'TODO:ここに出力内容を書く\');',
		);

		$methodArg = '';
		foreach ($arguments as $arg) {
			$matches = array();
			if (preg_match('/^([\$_0-9a-zA-Z]+)( \= )?(.*)/', $arg, $matches)) {
				if (! $methodArg) {
					$processes[] = '';
					$processes[] = '//データ生成';
				}
				$processes[] = $matches[1] . ' = ' . ($matches[3] ? $matches[3] : 'null') . ';';
				$methodArg .= ', ' . $matches[1];
			}
		}
		$processes[] = '';
		$processes[] = '//テスト実施';
		if (preg_match('/@return void/', $param[2])) {
			$processes[] = '$this->' . $function . '(' . substr($methodArg, 2) . ');';
		} else {
			$processes[] = '$result = $this->' . $function . '(' . substr($methodArg, 2) . ');';
			$processes[] = '';
			$processes[] = '//チェック';
			$processes[] = '//TODO:assertを書く';
			$processes[] = 'debug($result);';
		}

		$output .=
			$this->_classMethod(
				$function . '()のテスト',
				array(
					'@return void',
				),
				'test' . ucfirst($function) . '()',
				$processes
			) .
			'}' .
			'' . chr(10) .
			'';

		$this->createFile(Inflector::camelize(ucfirst($function)) . 'Test.php', $output);
		$this->deleteFile('empty');
	}

}

