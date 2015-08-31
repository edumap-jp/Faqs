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
				'Comment' => 'Comments.Comment',
			)
		),
		'Categories.Category',
		'Comments.Comment',
		'NetCommons.OriginalKey'
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
			'is_auto_translated' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Create announcement data
 *
 * @param int $roomId rooms.id
 * @return array
 */
	public function createFaq($roomId) {
		$this->FaqSetting = ClassRegistry::init('Faqs.FaqSetting');

		$faq = $this->create(array(
			'id' => null,
			'key' => null,
			'block_id' => null,
			'status' => null,
			'name' => __d('faqs', 'New FAQ %s', date('YmdHis')),
		));
		$faq = Hash::merge($faq, $this->createBlock(array(
			'room_id' => $roomId,
		)));
		$faq = Hash::merge($faq, $this->FaqSetting->create(array(
			'id' => null,
		)));

		return $faq;
	}

/**
 * Get Faq data
 *
 * @param int $blockId blocks.id
 * @param int $roomId rooms.id
 * @return array
 */
	public function getFaq($blockId, $roomId) {
		$this->FaqSetting = ClassRegistry::init('Faqs.FaqSetting');

		$conditions = array(
			'Block.id' => $blockId,
			'Block.room_id' => $roomId,
		);

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
			'conditions' => $conditions,
		));

		if (! $faq) {
			return $faq;
		}

		return $faq[0];
	}

/**
 * Save faq
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveFaq($data) {
		$this->loadModels([
			'Faq' => 'Faqs.Faq',
			'FaqSetting' => 'Faqs.FaqSetting',
			//'Category' => 'Categories.Category',
			'Block' => 'Blocks.Block',
			'Frame' => 'Frames.Frame',
		]);

		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		//バリデーション
		if (! $this->validateFaq($data)) {
			return false;
		}
		if (! $this->FaqSetting->validateFaqSetting($data['FaqSetting'])) {
			$this->validationErrors = Hash::merge($this->validationErrors, $this->FaqSetting->validationErrors);
			return false;
		}

		try {
//			//ブロックの登録
//			$block = $this->Block->saveByFrameId($data['Frame']['id']);

			//登録処理
//			$this->data['Faq']['block_id'] = (int)$block['Block']['id'];
			if (! $faq = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$this->FaqSetting->data['FaqSetting']['faq_key'] = $faq['Faq']['key'];
			if (! $this->FaqSetting->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

//			$data['Block']['id'] = (int)$block['Block']['id'];
//			$data['Block']['key'] = $block['Block']['key'];
//			$this->Category->saveCategories($data);

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
 * validate faq
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 */
	public function validateFaq($data) {
		$this->set($data);
		$this->validates();
		if ($this->validationErrors) {
			return false;
		}

//		if (in_array('faqSetting', $contains, true)) {
//			if (! $this->FaqSetting->validateFaqSetting($data)) {
//				$this->validationErrors = Hash::merge($this->validationErrors, $this->FaqSetting->validationErrors);
//				return false;
//			}
//		}
//
//		if (in_array('block', $contains, true)) {
//			if (! $this->Block->validateBlock($data)) {
//				$this->validationErrors = Hash::merge($this->validationErrors, $this->Block->validationErrors);
//				return false;
//			}
//		}
//
//		if (in_array('category', $contains, true)) {
//			if (! isset($data['Categories'])) {
//				$data['Categories'] = [];
//			}
//			if (! $data = $this->Category->validateCategories($data)) {
//				$this->validationErrors = Hash::merge($this->validationErrors, $this->Category->validationErrors);
//				return false;
//			}
//		}
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
			'Block' => 'Blocks.Block',
			'Category' => 'Categories.Category',
			'Comment' => 'Comments.Comment',
		]);

		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		$conditions = array(
			$this->alias . '.key' => $data['Faq']['key']
		);
		$faqs = $this->find('list', array(
				'recursive' => -1,
				'conditions' => $conditions,
			)
		);
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

			//コメントの削除
			$this->Comment->deleteByBlockKey($data['Block']['key']);

			//Categoryデータ削除
			$this->Category->deleteByBlockKey($data['Block']['key']);

			//Blockデータ削除
			$this->Block->deleteBlock($data['Block']['key']);

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

}
