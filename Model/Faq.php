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
				'BlockSetting' => 'Blocks.BlockSetting',
				'Category' => 'Categories.Category',
				'CategoryOrder' => 'Categories.CategoryOrder',
			)
		),
		'Categories.Category',
		'NetCommons.OriginalKey',
		//'M17n.M17n',
		'Workflow.WorkflowComment',
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Block' => array(
			'className' => 'Blocks.Block',
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
			'className' => 'Faqs.FaqQuestion',
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
 * Constructor. Binds the model's database table to the object.
 *
 * @param bool|int|string|array $id Set this ID for this model on startup,
 * can also be an array of options, see above.
 * @param string $table Name of database table to use.
 * @param string $ds DataSource connection name.
 * @see Model::__construct()
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->loadModels([
			'Faq' => 'Faqs.Faq',
			'FaqSetting' => 'Faqs.FaqSetting',
			'FaqQuestionOrder' => 'Faqs.FaqQuestionOrder',
		]);
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
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'FAQ Name')),
					'allowEmpty' => false,
					'required' => true,
				),
			),
		));

		if (isset($this->data['FaqSetting'])) {
			$this->FaqSetting->set($this->data['FaqSetting']);
			if (! $this->FaqSetting->validates()) {
				$this->validationErrors = Hash::merge(
					$this->validationErrors, $this->FaqSetting->validationErrors
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
		//FaqSetting登録
		if (isset($this->data['FaqSetting'])) {
			$this->FaqSetting->set($this->data['FaqSetting']);
			$this->FaqSetting->save(null, false);
		}

		return true;
	}

/**
 * Create Faq data
 *
 * @return array
 */
	public function createFaq() {
		$faq = $this->createAll(array(
			'Faq' => array(
				'name' => __d('faqs', 'New FAQ %s', date('YmdHis')),
			),
		));
		$faq = Hash::merge($faq, $this->FaqSetting->getFaqSetting());

		return $faq;
	}

/**
 * Get Faq data
 *
 * @return array
 */
	public function getFaq() {
		$fields = Hash::merge(
			array(
				$this->alias . '.*',
				$this->Block->alias . '.*',
			),
			Hash::get($this->belongsTo, 'TrackableCreator.fields', array()),
			Hash::get($this->belongsTo, 'TrackableUpdater.fields', array())
		);

		$faq = $this->find('all', array(
			'recursive' => 0,
			'fields' => $fields,
			'conditions' => $this->getBlockConditionById(),
		));

		if (! $faq) {
			return false;
		}
		return Hash::merge($faq[0], $this->FaqSetting->getFaqSetting());
	}

/**
 * Save Faq
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveFaq($data) {
		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
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

			$this->FaqQuestion->blockKey = $data['Block']['key'];
			$conditions = array($this->FaqQuestion->alias . '.faq_id' => $faqs);
			if (! $this->FaqQuestion->deleteAll($conditions, false, true)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$conditions = array($this->FaqQuestionOrder->alias . '.faq_key' => $data['Faq']['key']);
			if (! $this->FaqQuestionOrder->deleteAll($conditions, false)) {
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
