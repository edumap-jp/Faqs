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

App::uses('FaqsAppModel', 'Faqs.Model');
App::uses('BlockSettingBehavior', 'Blocks.Model/Behavior');

/**
 * FaqSetting Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Model
 */
class FaqSetting extends FaqsAppModel {

/**
 * Custom database table name
 *
 * @var string
 */
	public $useTable = 'blocks';

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

	///**
	// * Called during validation operations, before validation. Please note that custom
	// * validation rules can be defined in $validate.
	// *
	// * @param array $options Options passed from Model::save().
	// * @return bool True if validate operation should continue, false to abort
	// * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
	// * @see Model::save()
	// */
	//	public function beforeValidate($options = array()) {
	//		$this->validate = Hash::merge($this->validate, array(
	//			'faq_key' => array(
	//				'notBlank' => array(
	//					'rule' => array('notBlank'),
	//					'message' => __d('net_commons', 'Invalid request.'),
	//					'allowEmpty' => false,
	//					'required' => true,
	//					'on' => 'update', // Limit validation to 'create' or 'update' operations
	//				),
	//			),
	//			'use_workflow' => array(
	//				'boolean' => array(
	//					'rule' => array('boolean'),
	//					'message' => __d('net_commons', 'Invalid request.'),
	//				),
	//			),
	//			'use_like' => array(
	//				'boolean' => array(
	//					'rule' => array('boolean'),
	//					'message' => __d('net_commons', 'Invalid request.'),
	//				),
	//			),
	//			'use_unlike' => array(
	//				'boolean' => array(
	//					'rule' => array('boolean'),
	//					'message' => __d('net_commons', 'Invalid request.'),
	//				),
	//			),
	//		));
	//
	//		return parent::beforeValidate($options);
	//	}

/**
 * FaqSettingデータ新規作成
 *
 * @return array
 */
	public function createFaqSetting() {
		$faqSetting = $this->createAll();
		/** @see BlockSettingBehavior::getBlockSetting() */
		/** @see BlockSettingBehavior::_createBlockSetting() */
		return Hash::merge($faqSetting, $this->getBlockSetting());
	}

/**
 * Get faq setting data
 *
 * @return array
 */
	public function getFaqSetting() {
		//public function getFaqSetting($faqKey) {
		//* @param string $faqKey faq.key
		//		$conditions = array(
		//			'faq_key' => $faqKey
		//		);

		$faqSetting = $this->find('first', array(
			'recursive' => -1,
			//'conditions' => $conditions,
			'conditions' => array(
				$this->alias . '.key' => Current::read('Block.key'),
				$this->alias . '.language_id' => Current::read('Language.id'),
			),
		));

		return $faqSetting;
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
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}
}
