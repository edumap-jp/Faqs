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

/**
 * FaqSetting Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Model
 */
class FaqSetting extends FaqsAppModel {

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
	);

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'faq_key' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'use_workflow' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Get faq setting data
 *
 * @param string $faqKey faq.key
 * @return array
 */
	public function getFaqSetting($faqKey) {
		$conditions = array(
			'faq_key' => $faqKey
		);

		$faqSetting = $this->find('first', array(
			'recursive' => -1,
			'conditions' => $conditions,
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
		$this->loadModels([
			'FaqSetting' => 'Faqs.FaqSetting',
		]);

		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		if (! $this->validateFaqSetting($data)) {
			return false;
		}

		try {
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$dataSource->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$dataSource->rollback();
			CakeLog::error($ex);
			throw $ex;
		}

		return true;
	}

/**
 * validate faqSettings
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 */
	public function validateFaqSetting($data) {
		$this->set($data);
		$this->validates();
		if ($this->validationErrors) {
			return false;
		}
		return true;
	}

}
