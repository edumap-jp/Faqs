<?php
/**
 * FaqQuestionsController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionsController', 'Faqs.Controller');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * FaqQuestionsController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller\FaqQuestionsController
 */
class FaqQuestionsControllerBeforeFilterTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.workflow.workflow_comment',
		'plugin.faqs.faq',
		'plugin.faqs.faq_setting',
		'plugin.faqs.faq_question',
		'plugin.faqs.faq_question_order',
	);

/**
 * Plugin name
 *
 * @var array
 */
	public $plugin = 'faqs';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'faq_questions';

/**
 * $this->Faq->getFaq()がfalseのテスト
 *
 * @param string $role ロール名
 * @param bool $isException Exceptionの有無
 * @return void
 */
	public function testFaqError() {
		$this->generateNc(Inflector::camelize($this->_controller));

		//ログイン
		TestAuthGeneral::login($this);

		//Faqs.Faq::getFaq()をモック
		$this->_mockForReturnFalse('Faqs.Faq', 'getFaq');

		//テスト実施
		$frameId = '6';
		$url = array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'index',
			'frame_id' => $frameId
		);
		$this->_testGetAction($url, null, 'BadRequestException', 'json');

		//ログアウト
		TestAuthGeneral::logout($this);
	}

}
