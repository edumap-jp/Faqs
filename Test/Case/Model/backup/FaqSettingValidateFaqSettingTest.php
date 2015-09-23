<?php
/**
 * Test of FaqSetting->validateFaqSetting()
 *
 * @property FaqSetting $FaqSetting
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqSettingTestBase', 'Faqs.Test/Case/Model');

/**
 * Test of FaqSetting->validateFaqSetting()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqSettingValidateFaqSettingTest extends FaqSettingTestBase {

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultData = array(
		'FaqSetting' => array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'use_workflow' => true,
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
		$result = $this->FaqSetting->validateFaqSetting($data);
		//戻り値チェック
		$expectMessage = 'Expect `' . $field . '` field, error data: ' . print_r($data, true);
		$this->assertFalse($result, $expectMessage);
		//validationErrorsチェック
		$this->assertEquals($this->FaqSetting->validationErrors, $expected);
		//終了処理
		$this->tearDown();
	}

/**
 * Expect to validate the FaqSetting
 *
 * @return void
 */
	public function test() {
		//データ生成
		$data = $this->__defaultData;

		//処理実行
		$result = $this->FaqSetting->validateFaqSetting($data);
		$this->assertTrue($result);
	}

/**
 * Expect FaqSetting `faq_key` error by notBlank error on update
 *
 * @return void
 */
	public function testKeyErrorByNotEmptyOnUpdate() {
		$field = 'faq_key';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['FaqSetting'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['FaqSetting'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect FaqSetting `use_workflow` error by boolean error
 *
 * @return void
 */
	public function testUseWorkflowErrorByBoolean() {
		$field = 'use_workflow';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = $this->__defaultData;

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施
		foreach ($this->validateBoolean as $check) {
			$data['FaqSetting'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

}
