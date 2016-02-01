<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4BlocksControllerEdit extends CreateController4Blocks {

	/**
	 * ブロック設定に関するXxxxController::add()とedit()とdelete()のテストコード生成
	 *
	 * @param array $param メソッドデータ配列
	 * @return bool 成功・失敗
	 */
	public function createTest($param) {
		$function = $param[0];
		$argument = $param[1];
		$className = $this->testFile['class'] . Inflector::camelize(ucfirst($function)) . 'Test';
		$testSuiteTest = 'BlocksControllerEditTest';
		$testSuitePlugin = 'Blocks';

		var_dump('_createBlocksControllerEdit');
	}

}