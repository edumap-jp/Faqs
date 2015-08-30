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
		'Categories.Category',
		'Comments.Comment',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsWorkflow',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentCreatable' => array('add', 'edit', 'delete'),
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
//	public $helpers = array(
//		'NetCommons.Token'
//	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Categories->initCategories();
	}

/**
 * index
 *
 * @return void
 */
	public function index() {
		if (! $this->viewVars['blockId']) {
			$this->autoRender = false;
			return;
		}
		if (! $this->initFaq()) {
			return;
		}

		//条件
		$conditions = $this->__setConditions();

		if (isset($this->params['named']['status'])) {
			$conditions['FaqQuestion.status'] = (int)$this->params['named']['status'];
		}
		if (isset($this->params['named']['category_id'])) {
			$conditions['FaqQuestion.category_id'] = (int)$this->params['named']['category_id'];
		}

		//取得
		$faqQuestions = $this->FaqQuestion->getFaqQuestions($conditions);

		$results = array(
			'faqQuestions' => $faqQuestions
		);
		//Viewにセット
		$results = $this->camelizeKeyRecursive($results);
		$this->set($results);
	}

/**
 * view
 *
 * @return void
 */
	public function view() {
		$faqQuestionKey = null;
		if (isset($this->params['pass'][1])) {
			$faqQuestionKey = $this->params['pass'][1];
		}
		$this->set('faqQuestionKey', $faqQuestionKey);

		//データ取得
		$this->__initFaqQuestion();
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'edit';

		//データ取得
		if (! $this->initFaq()) {
			return;
		}

		$faqQuestion = $this->FaqQuestion->create(
			array(
				'id' => null,
				'key' => null,
				'faq_id' => $this->viewVars['faq']['id'],
				'category_id' => null,
				'question' => null,
				'answer' => null,
			)
		);
		$faqQuestionOrder = $this->FaqQuestionOrder->create(
			array(
				'id' => null,
				'faq_key' => $this->viewVars['faq']['key'],
				'faq_question_key' => null,
			)
		);

		//POSTの場合、登録処理
		$data = array();
		if ($this->request->isPost()) {
			if (! $status = $this->NetCommonsWorkflow->parseStatus()) {
				return;
			}
			$data = Hash::merge(
				$this->data,
				['FaqQuestion' => ['status' => $status]]
			);

			$this->FaqQuestion->saveFaqQuestion($data);
			if ($this->handleValidationError($this->FaqQuestion->validationErrors)) {
				$this->redirectByFrameId();
				return;
			}

			unset($data['Faq']);
			$data['contentStatus'] = null;
			$data['comments'] = null;
		}

		//Viewにセット
		$data = Hash::merge(
			$faqQuestion, $faqQuestionOrder, $data,
			['contentStatus' => null, 'comments' => []]
		);
		$results = $this->camelizeKeyRecursive($data);
		$this->set($results);
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		$faqQuestionKey = null;
		if (isset($this->params['pass'][1])) {
			$faqQuestionKey = $this->params['pass'][1];
		}
		$this->set('faqQuestionKey', $faqQuestionKey);

		//データ取得
		if (! $this->__initFaqQuestion(['comments'])) {
			return;
		}

		$data = Hash::merge(
			$this->viewVars,
			['contentStatus' => $this->viewVars['faqQuestion']['status']]
		);

		//POSTの場合、登録処理
		if ($this->request->isPost()) {
			if (! $status = $this->NetCommonsWorkflow->parseStatus()) {
				return;
			}
			$data = Hash::merge(
				$this->data,
				array('FaqQuestion' => array(
					'status' => $status,
					'created_user' => $this->viewVars['faqQuestion']['createdUser']
				))
			);
			unset($data['FaqQuestion']['id']);

			$this->FaqQuestion->saveFaqQuestion($data);

			if ($this->handleValidationError($this->FaqQuestion->validationErrors)) {
				$this->redirectByFrameId();
				return;
			}

			unset($data['Faq']);
			$data['contentStatus'] = null;
			$data['comments'] = null;
		}

		$results = $this->camelizeKeyRecursive($data);
		$this->set($results);
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		$faqQuestionKey = null;
		if (isset($this->params['pass'][1])) {
			$faqQuestionKey = $this->params['pass'][1];
		}
		$this->set('faqQuestionKey', $faqQuestionKey);

		//データ取得
		if (! $this->__initFaqQuestion()) {
			return;
		}

		if (! $this->request->isDelete()) {
			$this->throwBadRequest();
			return;
		}

		if (! $this->FaqQuestion->deleteFaqQuestion($this->data)) {
			$this->throwBadRequest();
			return;
		}

		$this->redirectByFrameId();
	}

/**
 * Get conditions function for getting the FaqQuestion data.
 *
 * @return array Conditions data
 */
	private function __setConditions() {
		//言語の指定
		$activeConditions = array(
			'FaqQuestion.is_active' => true,
		);
		$latestConditons = array();

		if ($this->viewVars['contentEditable']) {
			$activeConditions = array();
			$latestConditons = array(
				'FaqQuestion.is_latest' => true,
			);
		} elseif ($this->viewVars['contentCreatable']) {
			$activeConditions = array(
				'FaqQuestion.is_active' => true,
				'FaqQuestion.created_user !=' => (int)$this->viewVars['userId'],
			);
			$latestConditons = array(
				'FaqQuestion.is_latest' => true,
				'FaqQuestion.created_user' => (int)$this->viewVars['userId'],
			);
		}

		$conditions = array(
			'FaqQuestion.faq_id' => $this->viewVars['faq']['id'],
			'OR' => array($activeConditions, $latestConditons)
		);

		return $conditions;
	}

/**
 * Function do set into view with getting the FaqQuestion data.
 *
 * @param array $contains Optional result sets
 * @return bool True on success, False on failure
 */
	private function __initFaqQuestion($contains = []) {
		if (! $this->initFaq($contains)) {
			return false;
		}

		$conditions = $this->__setConditions();
		if (! $faqQuestion = $this->FaqQuestion->getFaqQuestion(
			$this->viewVars['faq']['id'],
			$this->viewVars['faqQuestionKey'],
			$conditions
		)) {
			$this->throwBadRequest();
			return false;
		}
		$faqQuestion = $this->camelizeKeyRecursive($faqQuestion);
		$this->set($faqQuestion);

		if (in_array('comments', $contains, true)) {
			$comments = $this->Comment->getComments(
				array(
					'plugin_key' => $this->params['plugin'],
					'content_key' => $this->viewVars['faqQuestionKey'],
				)
			);
			$comments = $this->camelizeKeyRecursive($comments);
			$this->set(['comments' => $comments]);
		}

		return true;
	}
}
