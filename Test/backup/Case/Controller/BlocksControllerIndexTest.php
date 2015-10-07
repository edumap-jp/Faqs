<?php
/**
 * Index test on BlocksController
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqBlocksController', 'Faqs.Controller');
App::uses('FaqsControllerTestBase', 'Faqs.Test/Case/Controller');

/**
 * Index test on BlocksController
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller
 */
class FaqBlocksControllerIndexTest extends FaqsControllerTestBase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		$this->generate(
			'Faqs.FaqBlocks',
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
		RolesControllerTest::login($this);

		$frameId = '100';
		$view = $this->testAction(
				'/faqs/faq_blocks/index/' . $frameId,
				array(
					'method' => 'get',
					'return' => 'view',
				)
			);
		$this->assertTextEquals('index', $this->controller->view);

		$this->assertTextContains('/frames/frames/edit/' . $frameId, $view);
		$this->assertTextContains('/faqs/faq_blocks/add/' . $frameId, $view);
		$this->assertTextContains('/faqs/faq_blocks/edit/' . $frameId . '/100', $view);
		$this->assertTextContains('/faqs/faq_blocks/edit/' . $frameId . '/101', $view);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * Expect index action without Block
 *
 * @return void
 */
	public function testWithoutBlock() {
		RolesControllerTest::login($this);

		$frameId = '103';
		$view = $this->testAction(
				'/faqs/faq_blocks/index/' . $frameId,
				array(
					'method' => 'get',
					'return' => 'view',
				)
			);
		$this->assertTextEquals('not_found', $this->controller->view);

		$this->assertTextContains('/faqs/faq_blocks/add/' . $frameId, $view);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * Expect index action without page
 *
 * @return void
 */
	public function testPageError() {
		$this->setExpectedException('NotFoundException');

		RolesControllerTest::login($this);

		$frameId = '100';
		$this->testAction(
				'/faqs/faq_blocks/index/' . $frameId . '/page:2',
				array(
					'method' => 'get',
					'return' => 'view',
				)
			);
		$this->assertTextEquals('index', $this->controller->view);

		AuthGeneralControllerTest::logout($this);
	}
}
