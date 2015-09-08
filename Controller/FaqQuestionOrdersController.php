<?php
/**
 * FaqQuestionOrders Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsAppController', 'Faqs.Controller');

/**
 * FaqQuestionOrders Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Controller
 */
class FaqQuestionOrdersController extends FaqsAppController {

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
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'edit' => 'content_editable',
			),
		),
		'Categories.Categories',
		'Paginator',
	);

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		if (! $this->__prepare()) {
			return;
		}

		if ($this->request->isPost()) {
				if ($this->FaqQuestionOrder->saveFaqQuestionOrders($this->data)) {
					$this->redirect(Current::backToPageUrl());
				}
				$this->handleValidationError($this->FaqQuestionOrder->validationErrors);

		} else {
			$this->Paginator->settings = array(
				'FaqQuestion' => array(
					'recursive' => 0,
					'order' => array('FaqQuestionOrder.weight' => 'asc'),
					'conditions' => $this->FaqQuestion->getWorkflowConditions(array(
						'FaqQuestion.faq_id' => $this->viewVars['faq']['id'],
					)),
					'limit' => PHP_INT_MAX
				)
			);
			$this->request->data['FaqQuestions'] = $this->Paginator->paginate('FaqQuestion');
			$this->request->data['Frame'] = Current::read('Frame');
			$this->request->data['Faq'] = $this->viewVars['faq'];
		}
	}

/**
 * Prepare
 *
 * @return void
 */
	private function __prepare() {
		//FAQデータ取得
		if (! Current::read('Block.id')) {
			$this->autoRender = false;
			return false;
		}
		if (! $faq = $this->Faq->getFaq()) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('faq', $faq['Faq']);
		return true;
	}

}
