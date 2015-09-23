<?php
/**
 * Test of Faq->deleteFaq()
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqTestBase', 'Faqs.Test/Case/Model');

/**
 * Test of Faq->deleteFaq()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqDeleteFaqTest extends FaqTestBase {

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultData = array(
		'Block' => array(
			'id' => '100',
			'key' => 'block_100'
		),
		'Faq' => array(
			'id' => '1',
			'key' => 'faq_1'
		)
	);

/**
 * Initiailze of Faq->deleteFaq()
 *
 * @param array $data delete data
 * @return void
 */
	private function __initAssert($data) {
		$count = $this->Faq->find('count', array(
			'recursive' => -1,
			'conditions' => array('key' => $data['Faq']['key']),
		));
		$this->assertNotEmpty($count, 'Faq->find(count)');

		$count = $this->FaqSetting->find('count', array(
			'recursive' => -1,
			'conditions' => array('faq_key' => $data['Faq']['key']),
		));
		$this->assertNotEmpty($count, 'FaqSetting->find(count)');

		$count = $this->FaqQuestion->find('count', array(
			'recursive' => -1,
			'conditions' => array('faq_id' => $data['Faq']['id']),
		));
		$this->assertNotEmpty($count, 'FaqQuestion->find(count)');

		$count = $this->FaqQuestionOrder->find('count', array(
			'recursive' => -1,
			'conditions' => array('faq_key' => $data['Faq']['key']),
		));
		$this->assertNotEmpty($count, 'FaqQuestionOrder->find(count)');

		$count = $this->Category->find('count', array(
			'recursive' => -1,
			'conditions' => array('block_id' => $data['Block']['id']),
		));
		$this->assertNotEmpty($count, 'Category->find(count)');

		$count = $this->CategoryOrder->find('count', array(
			'recursive' => -1,
			'conditions' => array('block_key' => $data['Block']['key']),
		));
		$this->assertNotEmpty($count, 'CategoryOrder->find(count)');
	}

/**
 * Assert of Faq->deleteFaq()
 *
 * @param array $data delete data
 * @return void
 */
	private function __assert($data) {
		$count = $this->Faq->find('count', array(
			'recursive' => -1,
			'conditions' => array('key' => $data['Faq']['key']),
		));
		$this->assertEquals(0, $count, 'Faq->find(count)');

		$count = $this->FaqSetting->find('count', array(
			'recursive' => -1,
			'conditions' => array('faq_key' => $data['Faq']['key']),
		));
		$this->assertEquals(0, $count, 'FaqSetting->find(count)');

		$count = $this->FaqQuestion->find('count', array(
			'recursive' => -1,
			'conditions' => array('faq_id' => $data['Faq']['id']),
		));
		$this->assertEquals(0, $count, 'FaqQuestion->find(count)');

		$count = $this->FaqQuestionOrder->find('count', array(
			'recursive' => -1,
			'conditions' => array('faq_key' => $data['Faq']['key']),
		));
		$this->assertEquals(0, $count, 'FaqQuestionOrder->find(count)');

		$count = $this->Category->find('count', array(
			'recursive' => -1,
			'conditions' => array('block_id' => $data['Block']['id']),
		));
		$this->assertEquals(0, $count, 'Category->find(count)');

		$count = $this->CategoryOrder->find('count', array(
			'recursive' => -1,
			'conditions' => array('block_key' => $data['Block']['key']),
		));
		$this->assertEquals(0, $count, 'CategoryOrder->find(count)');
	}

/**
 * Expect to delete the Faq
 *
 * @return void
 */
	public function test() {
		//テストデータ生成
		$data = $this->__defaultData;

		//事前チェック
		$this->__initAssert($data);

		//実施
		$result = $this->Faq->deleteFaq($data);
		$this->assertTrue($result);

		//チェック
		$this->__assert($data);
	}

/**
 * Expect to fail on Faq->deleteAll()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnDeleteAll() {
		$this->setExpectedException('InternalErrorException');

		$this->Faq = $this->getMockForModel('Faqs.Faq', array('deleteAll'));
		$this->Faq->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));

		//テストデータ生成
		$data = $this->__defaultData;

		//事前チェック
		$this->__initAssert($data);

		//実施
		$this->Faq->deleteFaq($data);
	}

/**
 * Expect to fail on FaqSetting->deleteAll()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnFaqSettingDeleteAll() {
		$this->setExpectedException('InternalErrorException');

		$this->FaqSetting = $this->getMockForModel('Faqs.FaqSetting', array('deleteAll'));
		$this->FaqSetting->expects($this->any())
			->method('deleteAll')
			->will($this->returnValue(false));

		//テストデータ生成
		$data = $this->__defaultData;

		//事前チェック
		$this->__initAssert($data);

		//実施
		$this->Faq->deleteFaq($data);
	}

/**
 * Expect to fail on FaqQuestion->deleteAll()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnFaqQuestionDeleteAll() {
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
		$this->Faq->deleteFaq($data);
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
		$this->Faq->deleteFaq($data);
	}

}
