<?php
/**
 * View/Helperのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * View/Helperのテストファイル生成クラス
 */
Class CreateViewHelper extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## View/Helperのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * View/Helperのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions();

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

		}
	}

}
