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
		output(sprintf('## View/Elementsのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * View/Elementsのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {

	}

}

