<?php
/**
 * Index test on FaqsController
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsController', 'Faqs.Controller');
App::uses('FaqsControllerTestBase', 'Faqs.Test/Case/Controller');

/**
 * Index test on FaqsController
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller
 */
class FaqsControllerIndexTest extends FaqsControllerTestBase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		$this->generate(
			'Faqs.Faqs',
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
 * Expect index action
 *
 * @return void
 */
	public function testIndex() {
		$frameId = '100';
		$this->testAction(
				'/faqs/faqs/index/' . $frameId,
				array(
					'method' => 'get',
					'return' => 'view',
				)
			);
		$this->assertTextEquals('index', $this->controller->view);
	}
}
