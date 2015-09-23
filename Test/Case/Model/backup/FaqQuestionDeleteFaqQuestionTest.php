<?php
/**
 * Test of FaqQuestion->deleteFaqQuestion()
 *
 * @property FaqQuestion $FaqQuestion
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionTestBase', 'Faqs.Test/Case/Model');

/**
 * Test of FaqQuestion->deleteFaqQuestion()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqQuestionDeleteFaqQuestionTest extends FaqQuestionTestBase {

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultData = array(
		'Faq' => array(
			'id' => '1',
			'key' => 'faq_1',
		),
		'FaqQuestion' => array(
			'id' => '1',
			'faq_id' => '1',
			'key' => 'faq_question_1',
		),
	);

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FaqQuestion->FaqQuestionOrder = ClassRegistry::init('Faqs.FaqQuestionOrder');
		$this->FaqQuestion->Comment = ClassRegistry::init('Comments.Comment');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FaqQuestion->FaqQuestionOrder);
		unset($this->FaqQuestion->Comment);
		parent::tearDown();
	}

/**
 * Initiailze of FaqQuestion->deleteFaqQuestion()
 *
 * @param array $data delete data
 * @return void
 */
	private function __initAssert($data) {
		$count = $this->FaqQuestion->find('count', array(
			'recursive' => -1,
			'conditions' => array('key' => $data['FaqQuestion']['key']),
		));
		$this->assertNotEmpty($count, 'FaqQuestion->find(count)');

		$count = $this->FaqQuestionOrder->find('count', array(
			'recursive' => -1,
			'conditions' => array('faq_question_key' => $data['FaqQuestion']['key']),
		));
		$this->assertNotEmpty($count, 'FaqQuestionOrder->find(count)');
	}

/**
 * Assert of FaqQuestion->deleteFaqQuestion()
 *
 * @param array $data delete data
 * @return void
 */
	private function __assert($data) {
		$count = $this->FaqQuestion->find('count', array(
			'recursive' => -1,
			'conditions' => array('key' => $data['FaqQuestion']['key']),
		));
		$this->assertEquals(0, $count, 'FaqQuestion->find(count)');

		$count = $this->FaqQuestionOrder->find('count', array(
			'recursive' => -1,
			'conditions' => array('faq_question_key' => $data['FaqQuestion']['key']),
		));
		$this->assertEquals(0, $count, 'FaqQuestionOrder->find(count)');
	}

/**
 * Expect to delete the FaqQuestion
 *
 * @return void
 */
	public function test() {
		//テストデータ生成
		$data = $this->__defaultData;

		//事前チェック
		$this->__initAssert($data);

		//実施
		$result = $this->FaqQuestion->deleteFaqQuestion($data);
		$this->assertTrue($result);

		//チェック
		$this->__assert($data);
	}

/**
 * Expect to fail on FaqQuestion->deleteAll()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnDeleteAll() {
		$this->setExpectedException('InternalErrorException');

		$this->FaqQuestion = $this->getMockForModel('Faqs.FaqQuestion', array('deleteAll'));
		$this->FaqQuestion->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));

		//テストデータ生成
		$data = $this->__defaultData;

		//事前チェック
		$this->__initAssert($data);

		//実施
		$this->FaqQuestion->deleteFaqQuestion($data);
	}

/**
 * Expect to fail on FaqQuestionOrder->deleteAll()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnFaqQuestionOrderDeleteAll() {
		$this->setExpectedException('InternalErrorException');

		$this->FaqQuestionOrder = $this->getMockForModel('Faqs.FaqQuestionOrder', array('deleteAll'));
		$this->FaqQuestionOrder->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));

		//テストデータ生成
		$data = $this->__defaultData;

		//事前チェック
		$this->__initAssert($data);

		//実施
		$this->FaqQuestion->deleteFaqQuestion($data);
	}

}
