<?php
/**
 * View test on FaqQuestionsController
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionsController', 'Faqs.Controller');
App::uses('FaqsControllerTestBase', 'Faqs.Test/Case/Controller');

/**
 * View test on FaqQuestionsController
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller
 */
class FaqQuestionsControllerViewTest extends FaqsControllerTestBase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		$this->generate(
			'Faqs.FaqQuestions',
			[
				'components' => [
					'Auth' => ['user'],
					'Session',
					'Security',
				]
			]
		);
		parent::setUp();
	}

/**
 * Expect view action
 *
 * @return void
 */
	public function testView() {
		//RolesControllerTest::login($this);
		//
		//$frameId = '100';
		//$view = $this->testAction(
		//		'/faqs/faq_blocks/index/' . $frameId,
		//		array(
		//			'method' => 'get',
		//			'return' => 'view',
		//		)
		//	);
		//$this->assertTextEquals('index', $this->controller->view);
		//
		//$this->assertTextContains('/frames/frames/edit/' . $frameId, $view);
		//$this->assertTextContains('/faqs/faq_blocks/add/' . $frameId, $view);
		//$this->assertTextContains('/faqs/faq_blocks/edit/' . $frameId . '/100', $view);
		//$this->assertTextContains('/faqs/faq_blocks/edit/' . $frameId . '/101', $view);
		//
		//AuthGeneralControllerTest::logout($this);
	}
}
