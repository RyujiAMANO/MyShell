<?php
/**
 * その他のテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * その他のテストファイル生成クラス
 */
Class CreateOther extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(sprintf('## その他のテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * その他のテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {

	}

}

