<?php
/**
 * FaqQuestionOrder Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsAppModel', 'Faqs.Model');

/**
 * FaqQuestionOrder Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Model
 */
class FaqQuestionOrder extends FaqsAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

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
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'faq_question_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'weight' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					//'required' => true,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Called before every deletion operation.
 *
 * @param bool $cascade If true records that depend on this record will also be deleted
 * @return bool True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforedelete
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function beforeDelete($cascade = true) {
		if (isset($this->data[$this->alias]['faq_question_key'])) {
			$order = $this->find('first', array(
					'recursive' => -1,
					'fields' => array('faq_key', 'weight'),
					'conditions' => array(
						'faq_question_key' => $this->data[$this->alias]['faq_question_key']
					),
				));

			$this->updateAll(
				array($this->alias . '.weight' => $this->alias . '.weight - 1'),
				array(
					$this->alias . '.weight > ' => $order[$this->alias]['weight'],
					$this->alias . '.faq_key' => $order[$this->alias]['faq_key'],
				)
			);
		}
		return true;
	}

/**
 * getMaxWeight
 *
 * @param string $faqKey faqs.key
 * @return int
 */
	public function getMaxWeight($faqKey) {
		$order = $this->find('first', array(
				'recursive' => -1,
				'fields' => array('weight'),
				'conditions' => array('faq_key' => $faqKey),
				'order' => array('weight' => 'DESC')
			));

		if (isset($order[$this->alias]['weight'])) {
			$weight = (int)$order[$this->alias]['weight'];
		} else {
			$weight = 0;
		}
		return $weight;
	}

/**
 * Save FaqQuestionOrder
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveFaqQuestionOrders($data) {
		$this->loadModels([
			'FaqQuestionOrder' => 'Faqs.FaqQuestionOrder',
		]);

		//トランザクションBegin
		$this->begin();

		//バリデーション
		if (! $this->validateMany($data['FaqQuestions'])) {
			return false;
		}

		try {
			//登録処理
			if (! $this->saveMany($data['FaqQuestions'], ['validate' => false])) {
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
