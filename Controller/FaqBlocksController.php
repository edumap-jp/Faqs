<?php
/**
 * BlocksController
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsAppController', 'Faqs.Controller');

/**
 * BlocksController
 *
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @package NetCommons\Faqs\Controller
 */
class FaqBlocksController extends FaqsAppController {

/**
 * layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * use models
 *
 * @var array
 */
	public $uses = array(
		'Blocks.Block',
		'Frames.Frame',
		'Faqs.Faq',
		'Faqs.FaqSetting',
		'Categories.Category',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'blockEditable' => array('index', 'add', 'edit', 'delete')
			),
		),
		'Paginator',
		'Categories.Categories',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Date',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('index');

		//タブの設定
		$this->initTabs('block_index', 'block_settings');
	}

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->Paginator->settings = array(
			'Faq' => array(
				'order' => array('Block.id' => 'desc'),
				'conditions' => array(
					'Block.language_id' => $this->viewVars['languageId'],
					'Block.room_id' => $this->viewVars['roomId'],
					'Block.plugin_key ' => $this->params['plugin'],
				),
				//'limit' => 1
			)
		);

		$faqs = $this->Paginator->paginate('Faq');
		if (! $faqs) {
			$this->view = 'not_found';
			return;
		}
//		$results = array(
//			'faqs' => $faqs
//		);
//		$results = $this->camelizeKeyRecursive($results);
//		$this->set($results);
		$this->set('faqs', $faqs);

		$this->request->data['Frame']['block_id'] = $this->viewVars['blockId'];
		$this->request->data['Frame']['id'] = $this->viewVars['frameId'];

	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'edit';

		$this->set('blockId', null);
		$faq = $this->Faq->create(
			array(
				'id' => null,
				'key' => null,
				'block_id' => null,
				'name' => __d('faqs', 'New FAQ %s', date('YmdHis')),
			)
		);
		$block = $this->Block->create(
			array('id' => null, 'key' => null)
		);

		$data = Hash::merge($faq, $block);

		if ($this->request->isPost()) {
			$data = $this->__parseRequestData();

			$this->Faq->saveFaq($data);
			if ($this->handleValidationError($this->Faq->validationErrors)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/faqs/faq_blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
			$data['Block']['id'] = null;
			$data['Block']['key'] = null;
			unset($data['Frame']);
		}

		$results = $this->camelizeKeyRecursive($data);
		$this->set($results);
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('blockId', (int)$this->params['pass'][1]);

		if (! $this->initFaq(['faqSetting'])) {
			return;
		}
		$this->Categories->initCategories();

		if ($this->request->isPost()) {
			$data = $this->__parseRequestData();
			$data['FaqSetting']['faq_key'] = $data['Faq']['key'];

			$this->Faq->saveFaq($data);
			if ($this->handleValidationError($this->Faq->validationErrors)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/faqs/faq_blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
			unset($data['Frame']);

			$results = $this->camelizeKeyRecursive($data);
			$this->set($results);
		}
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('blockId', (int)$this->params['pass'][1]);

		if (! $this->initFaq()) {
			return;
		}

		if ($this->request->isDelete()) {
			if ($this->Faq->deleteFaq($this->data)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/faqs/faq_blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
		}

		$this->throwBadRequest();
	}

/**
 * Parse data from request
 *
 * @return array
 */
	private function __parseRequestData() {
		$data = $this->data;
		if ($data['Block']['public_type'] === Block::TYPE_LIMITED) {
			//$data['Block']['from'] = implode('-', $data['Block']['from']);
			//$data['Block']['to'] = implode('-', $data['Block']['to']);
		} else {
			unset($data['Block']['from'], $data['Block']['to']);
		}

		return $data;
	}

}
