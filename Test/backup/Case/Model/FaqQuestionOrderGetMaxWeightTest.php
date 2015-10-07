<?php
/**
 * Test of FaqQuestionOrder->getMaxWeight()
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
 * Test of FaqQuestionOrder->getMaxWeight()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqQuestionOrderGetMaxWeightTest extends FaqQuestionOrderTestBase {

/**
 * Expect to get max weight the FaqQuestionOrder
 *
 * @return void
 */
	public function test() {
		$faqKey = 'faq_1';

		//処理実行
		$result = $this->FaqQuestionOrder->getMaxWeight($faqKey);

		//テスト実施
		$this->assertNotEmpty($result);
	}

/**
 * Expect to empty of FaqQuestionOrder->getMaxWeight()
 *
 * @return void
 */
	public function testEmpty() {
		$faqKey = 'faq_2';

		//処理実行
		$result = $this->FaqQuestionOrder->getMaxWeight($faqKey);

		//テスト実施
		$this->assertEmpty($result);
	}

}
