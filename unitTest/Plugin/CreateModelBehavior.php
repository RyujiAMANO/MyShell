<?php
/**
 * ModelBehaviorのテストファイル生成クラス
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 */

/**
 * ModelBehaviorのテストファイル生成クラス
 */
Class CreateModelBehavior extends CreateObject {

	/**
	 * コンストラクター
	 *
	 * @param array $testFile ファイルデータ
	 * @return void
	 */
	public function __construct($testFile = null) {
		output(chr(10) . '-*-*-*-*-*-*-*-*-*-*-' . chr(10));
		output(sprintf('## ModelBehaviorのテストコード生成(%s)', $testFile['dir'] . '/' . $testFile['file']));
		output(print_r($testFile, true));

		parent::__construct($testFile);
	}

	/**
	 * ModelBehaviorのテストコード生成
	 *
	 * @return void
	 */
	public function create() {
		$functions = $this->getFunctions();
		foreach ($functions as $param) {
			if (substr($param[0], 0, strlen('setup')) === 'setup') {
				continue;
			}

			output('---------------------' . chr(10));
			output(sprintf('#### テストファイル生成  %s(%s)', $param[0], $param[1]) . chr(10));

			if (substr($param[0], 0, strlen('beforeSave')) === 'beforeSave' ||
					substr($param[0], 0, strlen('afterSave')) === 'afterSave') {
				(new CreateModelBehavior4Event($this->testFile))->createTest(array('save'), array('$data' => 'fixture'));

			} elseif (substr($param[0], 0, strlen('beforeDelete')) === 'beforeDelete' ||
					substr($param[0], 0, strlen('afterDelete')) === 'afterDelete') {
				(new CreateModelBehavior4Event($this->testFile))->createTest(array('delete'), array('$data' => 'fixture'));

			} elseif (substr($param[0], 0, strlen('beforeFind')) === 'beforeFind' ||
					substr($param[0], 0, strlen('afterFind')) === 'afterFind') {
				(new CreateModelBehavior4Event($this->testFile))->createTest(array('find'), array('$type' => '\'first\'', '$query' => 'array()'));

			} elseif (substr($param[0], 0, strlen('beforeValidate')) === 'beforeValidate' ||
					substr($param[0], 0, strlen('afterValidate')) === 'afterValidate') {
				(new CreateModelBehavior4Event($this->testFile))->createTest(array('validates'), array('$data' => 'fixture'));

			} else {
				(new CreateModelBehavior4Other($this->testFile))->createTest($param);
			}
		}
	}

}

