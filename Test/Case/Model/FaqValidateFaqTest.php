<?php
/**
 * Test of Faq->validateFaq()
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
 * Test of Faq->validateFaq()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqValidateFaqTest extends FaqTestBase {

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
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Faq = ClassRegistry::init('Faqs.Faq');
		$this->Faq->FaqSetting = ClassRegistry::init('Faqs.FaqSetting');
		$this->Faq->Block = ClassRegistry::init('Blocks.Block');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Faq);
		unset($this->Faq->FaqSetting);
		unset($this->Faq->Block);
		parent::tearDown();
	}

/**
 * __assertValidationError
 *
 * @param string $field Field name
 * @param array $data Save data
 * @param array $expected Expected value
 * @return void
 */
	private function __assertValidationError($field, $data, $expected) {
		//初期処理
		$this->setUp();
		//validate処理実行
		$result = $this->Faq->validateFaq($data, ['faqSetting', 'block']);
		//戻り値チェック
		$expectMessage = 'Expect `' . $field . '` field, error data: ' . print_r($data, true);
		$this->assertFalse($result, $expectMessage);
		//validationErrorsチェック
		$this->assertEquals($this->Faq->validationErrors, $expected);
		//終了処理
		$this->tearDown();
	}

/**
 * Expect to validate the Faq
 *
 * @return void
 */
	public function test() {
		//データ生成
		$data = $this->__defaultData;

		//処理実行
		$result = $this->Faq->validateFaq($data, ['faqSetting', 'block']);
		$this->assertTrue($result);
	}

/**
 * Expect Faq `key` error by notBlank error on update
 *
 * @return void
 */
	public function testKeyErrorByNotEmptyOnUpdate() {
		$field = 'key';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['Faq'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['Faq'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect Faq `name` error by notBlank error on create
 *
 * @return void
 */
	public function testNameErrorByNotEmptyOnCreate() {
		$field = 'name';
		$message = sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'FAQ'));

		//データ生成
		$data = $this->__defaultData;
		unset($data['Faq']['id'], $data['Block']['id'], $data['FaqSetting']['id']);

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['Faq'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['Faq'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect Faq `name` error by notBlank error on update
 *
 * @return void
 */
	public function testNameErrorByNotEmptyOnUpdate() {
		$field = 'name';
		$message = sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'FAQ'));

		//テストデータ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['Faq'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['Faq'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect FaqSetting validation error
 *
 * @return void
 */
	public function testFaqSettingValidationError() {
		$field = 'faq_key';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施
		$data['FaqSetting'][$field] = '';
		$this->__assertValidationError($field, $data, $expected);
	}

/**
 * Expect Block validation error
 *
 * @return void
 */
	public function testBlockValidationError() {
		$field = 'room_id';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施
		$data['Block'][$field] = 'aa';
		$this->__assertValidationError($field, $data, $expected);
	}

}
