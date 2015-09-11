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
		'Faqs.FaqQuestion',
		'Faqs.FaqQuestionOrder',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.Permission',
		'Paginator',
		'Categories.Categories',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
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

		if (! $faq = $this->Faq->getFaq()) {
			$this->setAction('throwBadRequest');
			return false;
		}
		$this->set('faq', $faq['Faq']);
	}

/**
 * index
 *
 * @return void
 */
	public function index() {
		//条件
		$conditions = array(
			'FaqQuestion.faq_id' => $this->viewVars['faq']['id'],
		);
		if (isset($this->params['named']['category_id'])) {
			$conditions['FaqQuestion.category_id'] = $this->params['named']['category_id'];
		}

		//取得
		$faqQuestions = $this->FaqQuestion->getWorkflowContents('all', array(
			'recursive' => 0,
			'conditions' => $conditions
		));
		$this->set('faqQuestions', $faqQuestions);
	}

/**
 * view
 *
 * @return void
 */
	public function view() {
		//参照権限チェック
		if (! $this->FaqQuestion->canReadWorkflowContent()) {
			$this->throwBadRequest();
			return false;
		}

		$faqQuestionKey = null;
		if (isset($this->params['pass'][1])) {
			$faqQuestionKey = $this->params['pass'][1];
		}

		$faqQuestion = $this->FaqQuestion->getWorkflowContents('first', array(
			'recursive' => 0,
			'conditions' => array(
				$this->FaqQuestion->alias . '.faq_id' => $this->viewVars['faq']['id'],
				$this->FaqQuestion->alias . '.key' => $faqQuestionKey
			)
		));

		$this->set('faqQuestion', $faqQuestion);
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'edit';

		//投稿権限チェック
		if (! $this->FaqQuestion->canCreateWorkflowContent()) {
			$this->throwBadRequest();
			return false;
		}

		if ($this->request->isPost()) {
			//登録処理
			if (! $status = $this->Workflow->parseStatus()) {
				return;
			}
			$data = $this->data;
			$data['FaqQuestion']['status'] = $status;
			unset($data['FaqQuestion']['id']);

			if ($this->FaqQuestion->saveFaqQuestion($data)) {
				$this->redirect(Current::backToPageUrl());
				return;
			}
			$this->NetCommons->handleValidationError($this->FaqQuestion->validationErrors);

		} else {
			//表示処理
			$this->request->data = Hash::merge($this->request->data,
				$this->FaqQuestion->create(array(
					'faq_id' => $this->viewVars['faq']['id'],
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
		$faqQuestionKey = $this->params['pass'][1];
		if ($this->request->isPut()) {
			$faqQuestionKey = $this->data['FaqQuestion']['key'];
		}
		$faqQuestion = $this->FaqQuestion->getWorkflowContents('first', array(
			'recursive' => 0,
			'conditions' => array(
				$this->FaqQuestion->alias . '.faq_id' => $this->viewVars['faq']['id'],
				$this->FaqQuestion->alias . '.key' => $faqQuestionKey
			)
		));

		//編集権限チェック
		if (! $this->FaqQuestion->canEditWorkflowContent($faqQuestion)) {
			$this->throwBadRequest();
			return false;
		}

		if ($this->request->isPut()) {
			//登録処理
			if (! $status = $this->Workflow->parseStatus()) {
				return;
			}
			$data = $this->data;
			$data['FaqQuestion']['status'] = $status;
			unset($data['FaqQuestion']['id']);

			if ($this->FaqQuestion->saveFaqQuestion($data)) {
				$this->redirect(Current::backToPageUrl());
				return;
			}
			$this->NetCommons->handleValidationError($this->FaqQuestion->validationErrors);

		} else {
			//表示処理
			$this->request->data = $faqQuestion;
			$this->request->data['Faq'] = $this->viewVars['faq'];
			$this->request->data['Frame'] = Current::read('Frame');
			$this->request->data['Block'] = Current::read('Block');
		}

		$comments = $this->FaqQuestion->getCommentsByContentKey($this->request->data['FaqQuestion']['key']);
		$this->set('comments', $comments);
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if (! $this->request->isDelete()) {
			$this->throwBadRequest();
			return;
		}

		//データ取得
		$faqQuestion = $this->FaqQuestion->getWorkflowContents('first', array(
			'recursive' => -1,
			'conditions' => array(
				$this->FaqQuestion->alias . '.faq_id' => $this->data['FaqQuestion']['faq_id'],
				$this->FaqQuestion->alias . '.key' => $this->data['FaqQuestion']['key']
			)
		));

		//削除権限チェック
		if (! $this->FaqQuestion->canDeleteWorkflowContent($faqQuestion)) {
			$this->throwBadRequest();
			return false;
		}

		if (! $this->FaqQuestion->deleteFaqQuestion($this->data)) {
			$this->throwBadRequest();
			return;
		}

		$this->redirect(Current::backToPageUrl());
	}
}
