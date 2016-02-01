<?php
/**
 * Modelのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Modelのテストファイル生成クラス
 */
Class CreateModel4 extends CreateObject {

	/**
	 * デストラクター.
	 */
	function __destruct() {
		//親クラスの__destruct()を実行させないため
	}

	/**
	 * Workflowのモデルかどうかチェック
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
	 * Modelのメンバ変数
	 *
	 * @param string $function メソッド名
	 * @return string
	 */
	protected function _getClassVariable($function) {
		return
			$this->_classVariableFixtures() .
			$this->_classVariablePlugin() .
			$this->_classVariable(
					'Model name',
					'string',
					'protected',
					'_modelName',
					array(
						'\'' . $this->testFile['class'] . '\';',
					)
			) .
			$this->_classVariable(
					'Method name',
					'string',
					'protected',
					'_methodName',
					array(
						'\'' . $function . '\';',
					)
			);
	}

}

