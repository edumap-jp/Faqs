<?php
/**
 * Faq::saveFaq()のテスト
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqTestBase', 'Faqs.Test/Case/Model/Faq');

/**
 * Faq::saveFaq()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqSaveFaqTest extends FaqTestBase {

/**
 * 正常テスト
 *
 * @return void
 */
	public function testSaveFaq() {
		//事前データセット
		Current::$current['Block']['id'] = '2';
		Current::$current['Room']['id'] = '1';
		Current::$current['Language']['id'] = '2';
		$data = $this->data;

		//処理実行
		$result = $this->Faq->saveFaq($data);
		$this->assertTrue($result);
		$result = $this->Faq->getFaq();
		$this->assertEquals($result['Block']['name'], $result['Faq']['name']);
		unset($result['Block']['name']);

		//期待値
		$expected = $data;
		unset($expected['Frame']);

		//評価
		$this->_assertData($expected, $result);
	}

/**
 * FAQ追加テスト
 *
 * @param array $data Test data
 * @return void
 */
	public function testAddFaq($data = array()) {
		//事前データセット
		Current::$current['Block']['id'] = null;
		Current::$current['Room']['id'] = '1';
		Current::$current['Language']['id'] = '2';

		$data = Hash::merge(array(
			'Frame' => array(
				'id' => '6',
			)),
			$this->Faq->createAll(array(
				'Block' => array('plugin_key' => 'faqs'),
				'Faq' => array('name' => 'New Faq')
			)),
			$this->FaqSetting->create(),
			$data
		);
		unset($data['Block']['name']);

		//処理実行
		$result = $this->Faq->saveFaq($data);
		$this->assertTrue($result);

		$blockId = $this->Faq->Block->getLastInsertID();
		Current::$current['Block']['id'] = $blockId;

		$faqId = $this->Faq->getLastInsertID();
		$faqSettingId = $this->Faq->FaqSetting->getLastInsertID();

		$result = $this->Faq->getFaq();
		$this->assertEquals($result['Block']['name'], $result['Faq']['name']);
		unset($result['Block']['name']);

		//期待値
		$expected = Hash::merge($data, array(
			'Block' => array(
				'id' => $blockId,
				'key' => OriginalKeyBehavior::generateKey('Block', 'test')
			),
			'Faq' => array(
				'id' => $faqId,
				'block_id' => $blockId,
				'key' => OriginalKeyBehavior::generateKey('Faq', 'test')
			),
			'FaqSetting' => array(
				'id' => $faqSettingId,
				'faq_key' => OriginalKeyBehavior::generateKey('Faq', 'test')
			),
		));
		unset($expected['Frame']);

		//評価
		$this->_assertData($expected, $result);
	}

/**
 * フレームに配置した直後のテスト(Frame.block_idがnullのテスト)
 *
 * @return void
 */
	public function testFrameWithNullBlockId() {
		//事前チェック
		$before = $this->Frame->findById('14');
		$this->assertNull($before['Frame']['block_id']);

		//処理実行
		$data = array('Frame' => array('id' => '14'));
		$this->testAddFaq($data);

		//評価
		$after = $this->Frame->findById('14');
		$this->assertEquals($after['Frame']['block_id'], $this->Faq->Block->getLastInsertID());
	}

/**
 * Faq->save()のエラー
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnSave() {
		$this->setExpectedException('InternalErrorException');

		$data = $this->data;

		$this->Faq = $this->getMockForModel('Faqs.Faq', array('save'));
		$this->Faq->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->Faq->saveFaq($data);
	}

/**
 * FaqSetting->save()のエラー
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnFaqSettingSave() {
		$this->setExpectedException('InternalErrorException');

		$data = $this->data;

		$this->FaqSetting = $this->getMockForModel('Faqs.FaqSetting', array('save'));
		$this->FaqSetting->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->Faq->saveFaq($data);
	}

/**
 * Faq->validates()のエラー
 * ※ただし、詳細なチェックは別で行う
 *
 * @return void
 */
	public function testValidationError() {
		$data = $this->data;

		$this->Faq = $this->getMockForModel('Faqs.Faq', array('validates'));
		$this->Faq->expects($this->any())
			->method('validates')
			->will($this->returnValue(false));

		//処理実行
		$result = $this->Faq->saveFaq($data);
		$this->assertFalse($result);
	}

}
