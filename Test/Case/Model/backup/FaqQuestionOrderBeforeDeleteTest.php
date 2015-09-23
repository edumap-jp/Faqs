<?php
/**
 * Test of FaqQuestionOrder->beforeDelete()
 *
 * @property FaqQuestion $FaqQuestion
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionOrderTestBase', 'Faqs.Test/Case/Model');

/**
 * Test of FaqQuestionOrder->beforeDelete()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqQuestionOrderBeforeDeleteTest extends FaqQuestionOrderTestBase {

/**
 * Expect to delete the FaqQuestion
 *
 * @return void
 */
	public function test() {
		//テストデータ生成
		$this->FaqQuestionOrder->data['FaqQuestionOrder'] = array(
			'faq_key' => 'faq_1',
			'faq_question_key' => 'faq_question_2',
			'weight' => '2'
		);

		//実施
		$result = $this->FaqQuestionOrder->beforeDelete();
		$this->assertTrue($result);

		//チェック
		$result = $this->FaqQuestionOrder->find('first', array(
			'recursive' => -1,
			'conditions' => array('faq_question_key' => 'faq_question_3'),
		));

		$this->assertEquals('2', $result['FaqQuestionOrder']['weight']);
	}

}
