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
		$this->output('[2] 管理プラグイン' . chr(10));
	}
}
