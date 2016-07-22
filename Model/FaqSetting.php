<?php
/**
 * FaqSetting Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlockBaseModel', 'Blocks.Model');
App::uses('BlockSettingBehavior', 'Blocks.Model/Behavior');

/**
 * FaqSetting Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Model
 */
class FaqSetting extends BlockBaseModel {

/**
 * Custom database table name
 *
 * @var string
 */
	public $useTable = false;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Blocks.BlockRolePermission',
		'Blocks.BlockSetting' => array(
			BlockSettingBehavior::FIELD_USE_WORKFLOW,
			BlockSettingBehavior::FIELD_USE_LIKE,
			BlockSettingBehavior::FIELD_USE_UNLIKE,
		),
	);

/**
 * Get faq setting data
 *
 * @return array
 * @see BlockSettingBehavior::getBlockSetting() 取得
 * @see BlockSettingBehavior::_createBlockSetting() 取得で空なら新規登録データ取得
 */
	public function getFaqSetting() {
		return $this->getBlockSetting();
	}

/**
 * Save faq settings
 *
 * @param array $data received post data
 * @return bool True on success, false on failure
 * @throws InternalErrorException
 */
	public function saveFaqSetting($data) {
		//トランザクションBegin
		$this->begin();

		$this->set($data);
		if (! $this->validates()) {
			return false;
		}

		try {
			$this->save(null, false);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}
}
