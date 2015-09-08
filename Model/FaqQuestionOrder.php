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
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'faq_question_key' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
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
		if (isset($this->data[$this->name]['faq_question_key'])) {
			$order = $this->find('first', array(
					'recursive' => -1,
					'fields' => array('faq_key', 'weight'),
					'conditions' => array(
						'faq_question_key' => $this->data[$this->name]['faq_question_key']
					),
				));

			$this->updateAll(
				array($this->name . '.weight' => $this->name . '.weight - 1'),
				array(
					$this->name . '.weight > ' => $order[$this->name]['weight'],
					$this->name . '.faq_key' => $order[$this->name]['faq_key'],
				)
			);
		}
		return true;
	}

/**
 * validate of faq question order
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 */
	public function validateFaqQuestionOrder($data) {
		$this->set($data);
		$this->validates();
		if ($this->validationErrors) {
			return false;
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
		if (! $this->validateMany($data['FaqQuestionOrders'])) {
			return false;
		}

//		$indexes = array_keys($data['FaqQuestionOrder']);
//		foreach ($indexes as $i) {
//			if (! $this->validateFaqQuestionOrder($data['FaqQuestionOrder'][$i])) {
//				return false;
//			}
//		}

		try {
			//登録処理
//			foreach ($indexes as $i) {
//				if (! $this->save($data['FaqQuestionOrder'][$i], false)) {
//					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
//				}
//			}
			if (! $this->saveMany($data['FaqQuestionOrders'], ['validate' => false])) {
				return false;
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
