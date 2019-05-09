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
		'Likes.Like',
		'NetCommons.OriginalKey',
		'Workflow.WorkflowComment',
		'Workflow.Workflow',
		'Mails.MailQueue' => array(
			'embedTags' => array(
				'X-QUESTION' => 'FaqQuestion.question',
				'X-ANSWER' => 'FaqQuestion.answer',
				'X-FAQ_NAME' => 'Faq.name',
				'X-URL' => array('controller' => 'faq_questions'),
			),
			'embedTagsWysiwyg' => array('X-ANSWER'),
		),
		'Topics.Topics' => array(
			'fields' => array(
				'title' => 'FaqQuestion.question',
				'summary' => 'FaqQuestion.answer',
				'path' => '/:plugin_key/faq_questions/view/:block_id/:content_key',
			),
		),
		'Wysiwyg.Wysiwyg' => array(
			'fields' => array('answer'),
		),
		//多言語
		'M17n.M17n' => array(
			'commonFields' => array('category_id')
		),
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
		//'Faq' => array(
		//	'className' => 'Faqs.Faq',
		//	'foreignKey' => 'faq_id',
		//	'conditions' => '',
		//	'fields' => '',
		//	'order' => ''
		//),
		'Category' => array(
			'className' => 'Categories.Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		//'CategoryOrder' => array(
		//	'className' => 'Categories.CategoryOrder',
		//	'foreignKey' => false,
		//	'conditions' => 'CategoryOrder.category_key=Category.key',
		//	'fields' => '',
		//	'order' => ''
		//)
		'Block' => array(
			'className' => 'Blocks.Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => array(
				'content_count' => array(
					'FaqQuestion.is_origin' => true,
					'FaqQuestion.is_latest' => true
				),
			),
		),
	);

/**
 * Called before each find operation. Return false if you want to halt the find
 * call, otherwise return the (modified) query data.
 *
 * @param array $query Data used to execute this query, i.e. conditions, order, etc.
 * @return mixed true if the operation should continue, false if it should abort; or, modified
 *  $query to continue with new $query
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforefind
 */
	public function beforeFind($query) {
		//$this->idがある場合、登録処理として判断する
		if (Hash::get($query, 'recursive') > -1 && ! $this->id) {
			$belongsTo = $this->Category->bindModelCategoryLang('FaqQuestion.category_id');
			$this->bindModel($belongsTo, true);
		}
		return true;
	}

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
		$this->validate = ValidateMerge::merge($this->validate, array(
			'faq_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
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

		if (isset($this->data['FaqQuestionOrder'])) {
			$this->FaqQuestionOrder->set($this->data['FaqQuestionOrder']);
			if (! $this->FaqQuestionOrder->validates()) {
				$this->validationErrors = Hash::merge(
					$this->validationErrors, $this->FaqQuestionOrder->validationErrors
				);
				return false;
			}
		}

		return parent::beforeValidate($options);
	}

/**
 * Called before each save operation, after validation. Return a non-true result
 * to halt the save.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
 * @throws InternalErrorException
 * @see Model::save()
 */
	public function beforeSave($options = array()) {
		//FaqQuestionOrder登録
		if (isset($this->data['FaqQuestionOrder'])) {
			$this->FaqQuestionOrder->set($this->data['FaqQuestionOrder']);
		}
		if (isset($this->FaqQuestionOrder->data['FaqQuestionOrder']) &&
				! $this->FaqQuestionOrder->data['FaqQuestionOrder']['faq_question_key']) {

			$faqQuestionKey = $this->data[$this->alias]['key'];
			$this->FaqQuestionOrder->data['FaqQuestionOrder']['faq_question_key'] = $faqQuestionKey;

			$weight = $this->FaqQuestionOrder->getMaxWeight($this->data['Faq']['key']) + 1;
			$this->FaqQuestionOrder->data['FaqQuestionOrder']['weight'] = $weight;
			if (! $this->FaqQuestionOrder->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		return true;
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
			$this->contentKey = $data['FaqQuestion']['key'];
			$conditions = array($this->alias . '.key' => $data['FaqQuestion']['key']);
			if (! $this->deleteAll($conditions, false, true)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$conditions = array('faq_question_key' => $data['FaqQuestion']['key']);
			if (! $this->FaqQuestionOrder->deleteAll($conditions, false)) {
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
