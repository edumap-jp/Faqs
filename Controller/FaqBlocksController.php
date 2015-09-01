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
		'Faqs.Faq',
		'Faqs.FaqSetting',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'Categories.CategoryEdit',
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'blockEditable' => array('index', 'add', 'edit', 'delete')
			),
		),
		'Paginator',
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

		//CategoryEditComponentの削除
		if ($this->params['action'] === 'index') {
			$this->Components->unload('Categories.CategoryEdit');
		}
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
		$this->set('categoryMaps', null);

		if ($this->request->isPost()) {
			$data = $this->__parseRequestData();

			if ($this->Faq->saveFaq($data)) {
				$this->redirect('/faqs/faq_blocks/index/' . $this->viewVars['frameId']);
			}
			$this->handleValidationError($this->Faq->validationErrors);

		} else {
			//初期データセット
			$this->request->data = $this->Faq->createFaq($this->viewVars['roomId']);
			$this->request->data['Frame'] = array(
				'id' => $this->viewVars['frameId'],
				'key' => $this->viewVars['frameKey']
			);
		}
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		if (! isset($this->params['pass'][1])) {
			$this->throwBadRequest();
			return false;
		}

		if ($this->request->isPut()) {
			$data = $this->__parseRequestData();
			if ($this->Faq->saveFaq($data)) {
				$this->redirect('/faqs/faq_blocks/index/' . $this->viewVars['frameId']);
			}
			$this->handleValidationError($this->Faq->validationErrors);

		} else {
			//初期データセット
			if (! $faq = $this->Faq->getFaq($this->params['pass'][1], $this->viewVars['roomId'])) {
				$this->throwBadRequest();
				return false;
			}
			$this->set('blockId', $faq['Block']['id']);
			$this->set('blockKey', $faq['Block']['key']);

			$this->request->data = Hash::merge($this->request->data, $faq);
			$this->request->data['Frame'] = array(
				'id' => $this->viewVars['frameId'],
				'key' => $this->viewVars['frameKey']
			);
		}
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if ($this->request->isDelete()) {
			if ($this->Faq->deleteFaq($this->data)) {
				$this->redirect('/faqs/faq_blocks/index/' . $this->viewVars['frameId']);
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
		$data = $this->request->data;
		if ($data['Block']['public_type'] === Block::TYPE_LIMITED) {
			//$data['Block']['from'] = implode('-', $data['Block']['from']);
			//$data['Block']['to'] = implode('-', $data['Block']['to']);
		} else {
			unset($data['Block']['from'], $data['Block']['to']);
		}

		return $data;
	}

}
