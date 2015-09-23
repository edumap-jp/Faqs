<?php
/**
 * Test of Faq->saveFaq()
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
 * Test of Faq->saveFaq()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqSaveFaqTest extends FaqTestBase {

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultData = array(
		'Frame' => array(
			'id' => '100',
		),
		'Block' => array(
			'id' => '100',
			'language_id' => '2',
			'room_id' => '1',
			'plugin_key' => 'faqs',
			'key' => 'block_100',
			'public_type' => '2',
			'from' => '2015-04-28 12:09:35',
			'to' => '2016-04-28 01:12:28',
		),
		'Faq' => array(
			'id' => '1',
			'key' => 'faq_1',
			'name' => 'faq name 100',
		),
		'FaqSetting' => array(
			'id' => '1',
			'faq_key' => 'faq_1',
		),
	);

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultMergeExcepted = array(
		'Block' => array(
			'name' => '',
		),
		'Faq' => array(
			'is_auto_translated' => false,
			'translation_engine' => null,
		),
		'FaqSetting' => array(
			'use_workflow' => true,
		),
	);

/**
 * Expect to save the Faq
 *
 * @return void
 */
	public function test() {
		//データ生成
		$data = $this->__defaultData;

		//処理実行
		$result = $this->Faq->saveFaq($data);
		$this->assertTrue($result);

		//成否のデータ取得
		$blockId = '100';
		$roomId = '1';
		$faq = $this->Faq->getFaq($blockId, $roomId);
		$faqSetting = $this->FaqSetting->findByFaqKey($faq['Faq']['key']);
		$result = Hash::merge($faq, $faqSetting);

		//期待値の生成
		$expected = $data;
		$expected = Hash::remove($expected, 'Frame');
		$expected = Hash::merge(
			$expected,
			$this->__defaultMergeExcepted,
			array(
				'Faq' => array(
					'block_id' => $blockId
				),
			)
		);

		//テスト実施
		$this->_assertArray($expected, $result);
	}

/**
 * Expect to save frame with null of blockId
 *
 * @return void
 */
	public function testFrameWithNullBlockId() {
		$roomId = '2';
		$data = Hash::merge($this->__defaultData, array(
			'Frame' => array(
				'id' => '102',
			),
			'Block' => array(
				'id' => '',
				'room_id' => $roomId,
				'key' => '',
			),
			'Faq' => array(
				'id' => '',
				'key' => '',
				'name' => 'faq name 102',
			),
			'FaqSetting' => array(
				'id' => '',
				'faq_key' => '',
			),
		));

		//処理実行
		$result = $this->Faq->saveFaq($data);
		$this->assertTrue($result);

		//成否のデータ取得
		$blockId = $this->Faq->Block->getLastInsertID();
		$faqId = $this->Faq->getLastInsertID();
		$faqSettingId = $this->Faq->FaqSetting->getLastInsertID();

		$faq = $this->Faq->getFaq($blockId, $roomId);
		$faqSetting = $this->FaqSetting->findByFaqKey($faq['Faq']['key']);
		$result = Hash::merge($faq, $faqSetting);

		$this->assertNotEmpty($result['Faq']['key'], 'Faq.key');
		$this->assertNotEmpty($result['FaqSetting']['faq_key'], 'FaqSetting.faq_key');
		$this->assertNotEmpty($result['Block']['key'], 'Block.key');

		//期待値の生成
		$expected = $data;
		$expected = Hash::remove($expected, 'Frame');
		$expected = Hash::merge(
			$expected,
			$this->__defaultMergeExcepted,
			array(
				'Block' => array(
					'id' => $blockId,
					'key' => $faq['Block']['key']
				),
				'Faq' => array(
					'id' => $faqId,
					'block_id' => $blockId,
					'key' => $faq['Faq']['key']
				),
				'FaqSetting' => array(
					'id' => $faqSettingId,
					'faq_key' => $faq['Faq']['key']
				),
			)
		);

		//テスト実施
		$this->_assertArray($expected, $result);
	}

/**
 * Expect to add faq
 *
 * @return void
 */
	public function testAddFaq() {
		$roomId = '1';
		$data = Hash::merge($this->__defaultData, array(
			'Block' => array(
				'id' => '',
				'key' => '',
				'public_type' => '0',
				'from' => '',
				'to' => '',
			),
			'Faq' => array(
				'id' => '',
				'key' => '',
				'name' => 'faq name add',
			),
			'FaqSetting' => array(
				'id' => '',
				'faq_key' => '',
			),
		));

		//処理実行
		$result = $this->Faq->saveFaq($data);
		$this->assertTrue($result);

		//成否のデータ取得
		$blockId = $this->Faq->Block->getLastInsertID();
		$faqId = $this->Faq->getLastInsertID();
		$faqSettingId = $this->Faq->FaqSetting->getLastInsertID();

		$faq = $this->Faq->getFaq($blockId, $roomId);
		$faqSetting = $this->FaqSetting->findByFaqKey($faq['Faq']['key']);
		$result = Hash::merge($faq, $faqSetting);

		$this->assertNotEmpty($result['Faq']['key'], 'Faq.key');
		$this->assertNotEmpty($result['FaqSetting']['faq_key'], 'FaqSetting.faq_key');
		$this->assertNotEmpty($result['Block']['key'], 'Block.key');

		//期待値の生成
		$expected = $data;
		$expected = Hash::remove($expected, 'Frame');
		$expected = Hash::merge(
			$expected,
			$this->__defaultMergeExcepted,
			array(
				'Block' => array(
					'id' => $blockId,
					'key' => $faq['Block']['key']
				),
				'Faq' => array(
					'id' => $faqId,
					'block_id' => $blockId,
					'key' => $faq['Faq']['key']
				),
				'FaqSetting' => array(
					'id' => $faqSettingId,
					'faq_key' => $faq['Faq']['key']
				),
			)
		);

		//テスト実施
		$this->_assertArray($expected, $result);
	}

/**
 * Expect to validation error of the Faq
 *
 * @return void
 */
	public function testValidationError() {
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'Faq' => array(
				'name' => '',
			),
		));

		//処理実行
		$result = $this->Faq->saveFaq($data);
		$this->assertFalse($result);

		//成否のデータ取得
		$blockId = '100';
		$roomId = '1';
		$faq = $this->Faq->getFaq($blockId, $roomId);
		$faqSetting = $this->FaqSetting->findByFaqKey($faq['Faq']['key']);
		$result = Hash::merge($faq, $faqSetting);

		//期待値の生成
		$expected = $this->__defaultData;
		$expected = Hash::remove($expected, 'Frame');
		$expected = Hash::merge(
			$expected,
			$this->__defaultMergeExcepted,
			array(
				'Block' => array(
					'public_type' => '1',
					'from' => null,
					'to' => null,
				),
				'Faq' => array(
					'block_id' => $blockId
				),
			)
		);

		//テスト実施
		$this->_assertArray($expected, $result);
	}

/**
 * Expect to fail on Faq->save()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnSave() {
		$this->setExpectedException('InternalErrorException');

		$data = $this->__defaultData;

		$this->Faq = $this->getMockForModel('Faqs.Faq', array('save'));
		$this->Faq->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->Faq->saveFaq($data);
	}

/**
 * Expect to fail on FaqSetting->save()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnFaqSettingSave() {
		$this->setExpectedException('InternalErrorException');

		$data = $this->__defaultData;

		$this->FaqSetting = $this->getMockForModel('Faqs.FaqSetting', array('save'));
		$this->FaqSetting->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->Faq->saveFaq($data);
	}
}
