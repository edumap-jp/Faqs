<?php
/**
 * Faq Model
 *
 * @property Block $Block
 * @property FaqQuestionAnswer $FaqQuestionAnswer
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
class Faq extends FaqsAppModel {

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
		'Blocks.Block' => array(
			'name' => 'Faq.name',
			'loadModels' => array(
				'Category' => 'Categories.Category',
				'CategoryOrder' => 'Categories.CategoryOrder',
				'Comment' => 'Workflow.Comment',
			)
		),
		'Categories.Category',
		'NetCommons.OriginalKey',
		'Workflow.Comment',
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Block' => array(
			'className' => 'Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'FaqQuestion' => array(
			'className' => 'FaqQuestion',
			'foreignKey' => 'faq_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
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
		$this->loadModels([
			'FaqSetting' => 'Faqs.FaqSetting',
		]);

		$this->validate = Hash::merge($this->validate, array(
			'key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'block_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'FAQ')),
					'allowEmpty' => false,
					'required' => true,
				),
			),
		));

		if (! parent::beforeValidate($options)) {
			return false;
		}

		if (isset($this->data['FaqSetting'])) {
			$this->FaqSetting->set($this->data['FaqSetting']);
			if (! $this->FaqSetting->validates()) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->FaqSetting->validationErrors);
				return false;
			}
		}
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
		//FaqSetting登録
		if (isset($this->FaqSetting->data['FaqSetting'])) {
			if (! $this->FaqSetting->data['FaqSetting']['faq_key']) {
				$this->FaqSetting->data['FaqSetting']['faq_key'] = $this->data[$this->alias]['key'];
			}
			if (! $this->FaqSetting->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		parent::afterSave($created, $options);
	}

/**
 * Create Faq data
 *
 * @return array
 */
	public function createFaq() {
		$this->FaqSetting = ClassRegistry::init('Faqs.FaqSetting');

		$faq = $this->createAll(array(
			'Faq' => array(
				'name' => __d('faqs', 'New FAQ %s', date('YmdHis')),
			),
		));
		$faq = Hash::merge($faq, $this->FaqSetting->create());

		return $faq;
	}

/**
 * Get Faq data
 *
 * @return array
 */
	public function getFaq() {
		$this->FaqSetting = ClassRegistry::init('Faqs.FaqSetting');

		$faq = $this->find('all', array(
			'recursive' => -1,
			'fields' => array(
				$this->alias . '.*',
				$this->Block->alias . '.*',
				$this->FaqSetting->alias . '.*',
			),
			'joins' => array(
				array(
					'table' => $this->Block->table,
					'alias' => $this->Block->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->alias . '.block_id' . ' = ' . $this->Block->alias . ' .id',
					),
				),
				array(
					'table' => $this->FaqSetting->table,
					'alias' => $this->FaqSetting->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->alias . '.key' . ' = ' . $this->FaqSetting->alias . ' .faq_key',
					),
				),
			),
			'conditions' => $this->getBlockConditionById(),
		));

		if (! $faq) {
			return false;
		}
		return $faq[0];
	}

/**
 * Save Faq
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveFaq($data) {
		$this->loadModels([
			'Faq' => 'Faqs.Faq',
			'FaqSetting' => 'Faqs.FaqSetting',
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
			//登録処理
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

/**
 * Delete faq
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteFaq($data) {
		$this->loadModels([
			'Faq' => 'Faqs.Faq',
			'FaqSetting' => 'Faqs.FaqSetting',
			'FaqQuestion' => 'Faqs.FaqQuestion',
			'FaqQuestionOrder' => 'Faqs.FaqQuestionOrder',
		]);

		//トランザクションBegin
		$this->begin();

		$conditions = array(
			$this->alias . '.key' => $data['Faq']['key']
		);
		$faqs = $this->find('list', array(
			'recursive' => -1,
			'conditions' => $conditions,
		));
		$faqs = array_keys($faqs);

		try {
			if (! $this->deleteAll(array($this->alias . '.key' => $data['Faq']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			if (! $this->FaqSetting->deleteAll(array($this->FaqSetting->alias . '.faq_key' => $data['Faq']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			if (! $this->FaqQuestion->deleteAll(array($this->FaqQuestion->alias . '.faq_id' => $faqs), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			if (! $this->FaqQuestionOrder->deleteAll(array($this->FaqQuestionOrder->alias . '.faq_key' => $data['Faq']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			$this->deleteBlock($data['Block']['key']);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
