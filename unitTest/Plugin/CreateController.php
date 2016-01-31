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
	 * Workflowのコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isWorkflow() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/Workflow\.Workflow/', $file);
	}

	/**
	 * BlockPermissionのコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isBlockPermission() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/Blocks\.BlockRolePermissionForm/', $file);
	}

	/**
	 * Blockのコントローラかどうかチェック
	 *
	 * @return bool
	 */
	public function isBlock() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/Blocks\.BlockForm/', $file);
	}

	/**
	 * AppControllerにNetCommons.Permissionの有無チェック
	 *
	 * @return bool
	 */
	public function isAppControllerPermission() {
		if (! file_exists($this->testFile['path'])) {
			return false;
		}
		if (substr($this->testFile['class'], -1 * strlen('AppController') !== 'AppController')) {
			return false;
		}

		$file = file_get_contents($this->testFile['path']);
		return (bool)preg_match('/NetCommons\.Permission/', $file);
	}

	/**
	 * Controllerのテストコード生成
	 *
	 * @return bool 成功・失敗
	 */
	public function create() {
		$functions = $this->getFunctions();

		foreach ($functions as $param) {
			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if ($this->isWorkflow()) {

			} elseif ($this->isBlock()) {

			} elseif ($this->isBlockPermission()) {

			} else {

			}
		}
		if ($this->isAppControllerPermission()) {
			$this->_createAppController();
		}
	}

	/**
	 * ワークフローに関するXxxxController::index()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createWorkflowControllerIndex() {
		var_dump('_createWorkflowControllerIndex');
	}

	/**
	 * ワークフローに関するXxxxController::view()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createWorkflowControllerView() {
		var_dump('_createWorkflowControllerView');
	}

	/**
	 * ワークフローに関するXxxxController::add()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createWorkflowControllerAdd() {
		var_dump('_createWorkflowControllerAdd');
	}

	/**
	 * ワークフローに関するXxxxController::edit()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createWorkflowControllerEdit() {
		var_dump('_createWorkflowControllerEdit');
	}

	/**
	 * ワークフローに関するXxxxController::delete()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createWorkflowControllerDelete() {
		var_dump('_createWorkflowControllerDelete');
	}

	/**
	 * ブロック設定に関するXxxxController::index()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createBlockControllerIndex() {
		var_dump('_createBlockControllerIndex');
	}

	/**
	 * ブロック設定に関するXxxxController::add()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createBlockControllerAdd() {
		var_dump('_createWorkflowControllerAdd');
	}

	/**
	 * ブロック設定に関するXxxxController::edit()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createBlockControllerEdit() {
		var_dump('_createWorkflowControllerEdit');
	}

	/**
	 * ブロック設定に関するXxxxController::delete()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createBlockControllerDelete() {
		var_dump('_createWorkflowControllerDelete');
	}

	/**
	 * 権限設定に関するXxxxController::edit()のテストコード生成
	 *
	 * @param array $testFile ファイル名
	 * @return bool 成功・失敗
	 */
	protected function _createBlockPermissionControllerEdit() {
		var_dump('_createBlockPermissionControllerEdit');
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

