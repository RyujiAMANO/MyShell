<?php
/**
 * 管理プラグインの作成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */


/**
 * 管理プラグインの作成クラス
 */
Class SystemPlugin extends Plugin {

	/**
	 * ロード処理
	 *
	 * @return void
	 */
	public function load() {
		output('[2] 管理プラグイン' . chr(10));
		foreach ($this->testFiles as $testFile) {
			if ($testFile['type']) {
				$class = 'Create' . Inflector::camelize(strtr($testFile['type'], '/', ' '));
			} else {
				$class = 'CreateOther';
			}
			if (class_exists($class)) {
				(new $class($testFile))->create();
			}

//			switch ($testFile['type']) {
//				case 'Model':
//					$this->_createModel($testFile);
//					break;
//				case 'Model/Behavior':
//
//			}
		}
	}
}
