<?php
/**
 * FaqQuestions Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsAppController', 'Faqs.Controller');

/**
 * FaqQuestions Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Controller
 */
class FaqQuestionsController extends FaqsAppController {

/**
 * use models
 *
 * @var array
 */
	public $uses = array(
		'Faqs.Faq',
		'Faqs.FaqFrameSetting',
		'Faqs.FaqQuestion',
		'Faqs.FaqQuestionOrder',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add,edit,delete' => 'content_creatable',
			),
		),
		'Categories.Categories',
		'Paginator',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Likes.Like',
		'NetCommons.DisplayNumber',
		'Workflow.Workflow',
	);

/**
 * beforeRender
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (! Current::read('Block.id')) {
			$this->setAction('emptyRender');
			return false;
		}

		$faq = $this->Faq->getFaq();
		if (! $faq) {
			$this->setAction('throwBadRequest');
			return false;
		}
		$this->set('faq', $faq['Faq']);
		$this->set('faqSetting', $faq['FaqSetting']);

		$faqFrameSetting = $this->FaqFrameSetting->getFaqFrameSetting();
		$this->set('faqFrameSetting', $faqFrameSetting['FaqFrameSetting']);
	}

/**
 * index
 *
 * @return void
 */
	public function index() {
		$query = array();

		//条件
		$conditions = array(
			'FaqQuestion.faq_key' => $this->viewVars['faq']['key'],
		);
		if (isset($this->params['named']['category_id'])) {
			$conditions['FaqQuestion.category_id'] = $this->params['named']['category_id'];
		}
		$query['conditions'] = $this->FaqQuestion->getWorkflowConditions($conditions);

		//表示件数
		if (isset($this->params['named']['limit'])) {
			$query['limit'] = (int)$this->params['named']['limit'];
		} else {
			$query['limit'] = $this->viewVars['faqFrameSetting']['content_per_page'];
		}

		$query['recursive'] = 0;
		$this->Paginator->settings = $query;
		$faqQuestions = $this->Paginator->paginate('FaqQuestion');
		$this->set('faqQuestions', $faqQuestions);
	}

/**
 * view
 *
 * @return void
 */
	public function view() {
		$faqQuestionKey = Hash::get($this->request->params, 'key', null);
		$faqQuestion = $this->FaqQuestion->getWorkflowContents('first', array(
			'recursive' => 0,
			'conditions' => array(
				$this->FaqQuestion->alias . '.faq_key' => $this->viewVars['faq']['key'],
				$this->FaqQuestion->alias . '.key' => $faqQuestionKey
			)
		));
		if (! $faqQuestion) {
			return $this->throwBadRequest();
		}
		$this->set('faqQuestion', $faqQuestion);

		//新着データを既読にする
		$this->FaqQuestion->saveTopicUserStatus($faqQuestion);

		//Model表示
		if ($this->request->is('ajax')) {
			$this->viewClass = 'View';
			$this->layout = 'Faqs.modal';
		}
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'edit';

		if ($this->request->is('post')) {
			//登録処理
			$data = $this->data;
			$data['FaqQuestion']['status'] = $this->Workflow->parseStatus();
			unset($data['FaqQuestion']['id']);

			if ($this->FaqQuestion->saveFaqQuestion($data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl());
			}
			$this->NetCommons->handleValidationError($this->FaqQuestion->validationErrors);

		} else {
			//表示処理
			$this->request->data = Hash::merge($this->request->data,
				$this->FaqQuestion->create(array(
					'faq_key' => $this->viewVars['faq']['key'],
				)),
				$this->FaqQuestionOrder->create(array(
					'faq_key' => $this->viewVars['faq']['key'],
				))
			);
			$this->request->data['Faq'] = $this->viewVars['faq'];
			$this->request->data['Frame'] = Current::read('Frame');
			$this->request->data['Block'] = Current::read('Block');
		}
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		//データ取得
		$faqQuestionKey = $this->params['key'];
		if ($this->request->is('put')) {
			$faqQuestionKey = $this->data['FaqQuestion']['key'];
		}
		$faqQuestion = $this->FaqQuestion->getWorkflowContents('first', array(
			'recursive' => 0,
			'conditions' => array(
				$this->FaqQuestion->alias . '.faq_key' => $this->viewVars['faq']['key'],
				$this->FaqQuestion->alias . '.key' => $faqQuestionKey
			)
		));

		//編集権限チェック
		if (! $this->FaqQuestion->canEditWorkflowContent($faqQuestion)) {
			return $this->throwBadRequest();
		}

		if ($this->request->is('put')) {
			//登録処理
			$data = $this->data;
			$data['FaqQuestion']['status'] = $this->Workflow->parseStatus();
			unset($data['FaqQuestion']['id']);

			if ($this->FaqQuestion->saveFaqQuestion($data)) {
				return $this->redirect(NetCommonsUrl::backToIndexUrl());
			}
			$this->NetCommons->handleValidationError($this->FaqQuestion->validationErrors);

		} else {
			//表示処理
			$this->request->data = $faqQuestion;
			$this->request->data['Faq'] = $this->viewVars['faq'];
			$this->request->data['Frame'] = Current::read('Frame');
			$this->request->data['Block'] = Current::read('Block');
		}

		$comments = $this->FaqQuestion->getCommentsByContentKey(
			$this->request->data['FaqQuestion']['key']
		);
		$this->set('comments', $comments);
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if (! $this->request->is('delete')) {
			return $this->throwBadRequest();
		}

		//データ取得
		$faqQuestion = $this->FaqQuestion->getWorkflowContents('first', array(
			'recursive' => -1,
			'conditions' => array(
				$this->FaqQuestion->alias . '.faq_key' => $this->data['FaqQuestion']['faq_key'],
				$this->FaqQuestion->alias . '.key' => $this->data['FaqQuestion']['key']
			)
		));

		//削除権限チェック
		if (! $this->FaqQuestion->canDeleteWorkflowContent($faqQuestion)) {
			return $this->throwBadRequest();
		}

		if (! $this->FaqQuestion->deleteFaqQuestion($this->data)) {
			return $this->throwBadRequest();
		}

		$this->redirect(NetCommonsUrl::backToIndexUrl());
	}
}
