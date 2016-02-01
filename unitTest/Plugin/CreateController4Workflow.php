<?php
/**
 * Controllerのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * Controllerのテストファイル生成クラス
 */
Class CreateController4Workflow extends CreateController4 {

	/**
	 * Controllerのメンバ変数
	 *
	 * @param string $function メソッド名
	 * @return string
	 */
	protected function _getClassVariable($function) {
		return
			$this->_classVariableFixtures(array(
				'workflow.workflow_comment' => 'workflow.workflow_comment'
			)) .
			$this->_classVariablePlugin() .
			$this->_classVariable(
					'Controller name',
					'string',
					'protected',
					'_controller',
					array(
						'\'' . Inflector::underscore(substr($this->testFile['class'], 0, -1 * strlen('Controller'))) . '\';',
					)
			);
	}

}