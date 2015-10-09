<?php
/**
 * Test of FaqSetting->getFaqSetting()
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
 * Test of FaqSetting->getFaqSetting()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqSettingGetFaqSettingTest extends FaqSettingTestBase {

/**
 * Expect to get the FaqSetting
 *
 * @return void
 */
	public function test() {
		//データ生成
		$faqKey = 'faq_1';

		//処理実行
		$result = $this->FaqSetting->getFaqSetting($faqKey);

		//期待値の生成
		$expected = array(
			'FaqSetting' => array(
				'id' => '1',
				'faq_key' => $faqKey,
				'use_workflow' => true,
			),
		);

		//テスト実施
		$this->_assertArray($expected, $result);
	}

/**
 * Expect empty
 *
 * @return void
 */
	public function testEmpty() {
		//データ生成
		$faqKey = 'faq_999';

		//処理実行
		$result = $this->FaqSetting->getFaqSetting($faqKey);

		//期待値の生成
		$expected = array();

		//テスト実施
		$this->_assertArray($expected, $result);
	}

}
