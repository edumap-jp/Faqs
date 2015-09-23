<?php
/**
 * Test of FaqQuestionOrder->validateFaqQuestionOrder()
 *
 * @property FaqQuestionOrder $FaqQuestionOrder
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionOrderTestBase', 'Faqs.Test/Case/Model');

/**
 * Test of FaqQuestionOrder->validateFaqQuestionOrder()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqQuestionOrderValidateFaqQuestionOrderTest extends FaqQuestionOrderTestBase {

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultData = array(
		'FaqQuestionOrder' => array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'faq_question_key' => 'faq_question_1',
			'weight' => '1',
		),
	);

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
		$result = $this->FaqQuestionOrder->validateFaqQuestionOrder($data);
		//戻り値チェック
		$expectMessage = 'Expect `' . $field . '` field, error data: ' . print_r($data, true);
		$this->assertFalse($result, $expectMessage);
		//validationErrorsチェック
		$this->assertEquals($this->FaqQuestionOrder->validationErrors, $expected);
		//終了処理
		$this->tearDown();
	}

/**
 * Expect to validate the FaqQuestionOrders
 *
 * @return void
 */
	public function test() {
		//データ生成
		$data = $this->__defaultData;

		//処理実行
		$result = $this->FaqQuestionOrder->validateFaqQuestionOrder($data);
		$this->assertTrue($result);
	}

/**
 * Expect FaqQuestionOrder `faq_key` error by notBlank error on update
 *
 * @return void
 */
	public function testFaqKeyErrorByNotEmptyOnUpdate() {
		$field = 'faq_key';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['FaqQuestionOrder'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['FaqQuestionOrder'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect FaqQuestionOrder `faq_key` error by notBlank error on update
 *
 * @return void
 */
	public function testFaqQuestionKeyErrorByNotEmptyOnUpdate() {
		$field = 'faq_question_key';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['FaqQuestionOrder'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['FaqQuestionOrder'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect FaqQuestion `faq_id` error by notBlank error on update
 *
 * @return void
 */
	public function testWeightByNumber() {
		$field = 'weight';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施
		foreach ($this->validateNumber as $check) {
			$data['FaqQuestionOrder'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

}
