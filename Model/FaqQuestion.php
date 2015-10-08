<?php
/**
 * FaqQuestion Model
 *
 * @property Faq $Faq
 * @property Category $Category
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsAppModel', 'Faqs.Model');

/**
 * Faq Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Model
 */
class FaqQuestion extends FaqsAppModel {

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'NetCommons.OriginalKey',
		'Workflow.WorkflowComment',
		'Workflow.Workflow',
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'FaqQuestionOrder' => array(
			'className' => 'Faqs.FaqQuestionOrder',
			'foreignKey' => false,
			'conditions' => 'FaqQuestionOrder.faq_question_key=FaqQuestion.key',
			'fields' => '',
			'order' => array('FaqQuestionOrder.weight' => 'ASC')
		),
		'Faq' => array(
			'className' => 'Faqs.Faq',
			'foreignKey' => 'faq_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Category' => array(
			'className' => 'Categories.Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CategoryOrder' => array(
			'className' => 'Categories.CategoryOrder',
			'foreignKey' => false,
			'conditions' => 'CategoryOrder.category_key=Category.key',
			'fields' => '',
			'order' => ''
		)
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
			'faq_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					//'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),

			//status to set in PublishableBehavior.

			'question' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'Question')),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'answer' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'Answer')),
					'allowEmpty' => false,
					'required' => true,
				),
			),
		));

		if (! parent::beforeValidate($options)) {
			return false;
		}

		if (isset($this->data['FaqQuestionOrder'])) {
			$this->FaqQuestionOrder->set($this->data['FaqQuestionOrder']);
			if (! $this->FaqQuestionOrder->validates()) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->FaqQuestionOrder->validationErrors);
				return false;
			}
		}

		return true;
	}

/**
 * Called after each successful save operation.
 *
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return void
 * @throws InternalErrorException
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
 * @see Model::save()
 */
	public function afterSave($created, $options = array()) {
		//FaqQuestionOrder登録
		if (isset($this->FaqQuestionOrder->data['FaqQuestionOrder'])) {
			if (! $this->FaqQuestionOrder->data['FaqQuestionOrder']['faq_question_key']) {
				$this->FaqQuestionOrder->data['FaqQuestionOrder']['faq_question_key'] = $this->data[$this->alias]['key'];
				$this->FaqQuestionOrder->data['FaqQuestionOrder']['weight'] = $this->FaqQuestionOrder->getMaxWeight($this->data['Faq']['key']) + 1;
			}
			if (! $this->FaqQuestionOrder->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		parent::afterSave($created, $options);
	}

/**
 * Get FaqQuestion
 *
 * @param int $faqId faqs.id
 * @param string $faqQuestionKey faq_qestions.key
 * @param array $conditions find conditions
 * @return array FaqQuestion
 */
	public function getFaqQuestion($faqId, $faqQuestionKey, $conditions = []) {
		$conditions[$this->alias . '.faq_id'] = $faqId;
		$conditions[$this->alias . '.key'] = $faqQuestionKey;

		$faqQuestion = $this->find('first', array(
				'recursive' => 0,
				'conditions' => $conditions,
			)
		);

		return $faqQuestion;
	}

/**
 * Save FaqQuestion
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveFaqQuestion($data) {
		$this->loadModels([
			'FaqQuestion' => 'Faqs.FaqQuestion',
			'FaqQuestionOrder' => 'Faqs.FaqQuestionOrder',
		]);

		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {
			//FaqQuestion登録
			if (! $faqQuestion = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $faqQuestion;
	}

/**
 * Delete FaqQuestion
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteFaqQuestion($data) {
		$this->loadModels([
			'FaqQuestion' => 'Faqs.FaqQuestion',
			'FaqQuestionOrder' => 'Faqs.FaqQuestionOrder',
		]);

		//トランザクションBegin
		$this->begin();

		try {
			if (! $this->deleteAll(array($this->alias . '.key' => $data['FaqQuestion']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$conditions = array('faq_question_key' => $data['FaqQuestion']['key']);
			if (! $this->FaqQuestionOrder->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//コメントの削除
			$this->deleteCommentsByContentKey($data['FaqQuestion']['key']);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
