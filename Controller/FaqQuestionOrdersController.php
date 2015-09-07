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
		'Categories.Category',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentEditable' => array('edit'),
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
		$this->Categories->initCategories();

		if (! $this->initFaq()) {
			return;
		}

		$this->Paginator->settings = array(
			'FaqQuestion' => array(
				'order' => array('FaqQuestionOrder.weight' => 'asc'),
				'conditions' => array(
					'FaqQuestion.faq_id' => $this->viewVars['faq']['id'],
					'FaqQuestion.is_latest' => true,
				),
				'limit' => -1
			)
		);
		$faqQuestions = $this->Paginator->paginate('FaqQuestion');

		//POST処理
		if ($this->request->isPost()) {
			//登録処理
			$data = $this->data;

			$this->FaqQuestionOrder->saveFaqQuestionOrders($data);
			//validationError
			if ($this->handleValidationError($this->FaqQuestionOrder->validationErrors)) {
				//リダイレクト
				$this->redirect(Current::backToPageUrl());
				return;
			}
		}

		$results = array(
			'faqQuestions' => $faqQuestions
		);
		//Viewにセット
		$results = $this->camelizeKeyRecursive($results);
		$this->set($results);
	}

}
