<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## Controllerのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * Controllerのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions();

		foreach ($functions as $func) {
			$function = $func[0];
			$argument = $func[1];
			output(chr(10) . sprintf('#### テストファイル生成  %s(%s)', $function, $argument) . chr(10));

		}

	}

	/**
	 * XxxxControllerのテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createController() {
		var_dump('_createController');
	}

	/**
	 * XxxxAppControllerのテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createAppController() {
		var_dump('_createAppController');
	}

}

