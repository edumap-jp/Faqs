<?php
/**
 * Test of Faq->getFaq()
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
 * Test of Faq->getFaq()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqGetFaqTest extends FaqTestBase {

/**
 * Expect to get the Faq
 *
 * @return void
 */
	public function test() {
		//データ生成
		$blockId = '100';
		$roomId = '1';

		//処理実行
		$result = $this->Faq->getFaq($blockId, $roomId);
		$result = Hash::remove($result, 'Block');

		//期待値の生成
		$expected = array(
			'Faq' => array(
				'id' => '1',
				'key' => 'faq_1',
				'block_id' => $blockId,
				'name' => 'faq name 100',
				'is_auto_translated' => false,
				'translation_engine' => null,
			),
		);

		//テスト実施
		$this->_assertArray($expected, $result);
	}

/**
 * Expect empty by another roomId
 *
 * @return void
 */
	public function testAnotherRoomId() {
		//データ生成
		$blockId = '100';
		$roomId = '2';

		//処理実行
		$result = $this->Faq->getFaq($blockId, $roomId);
		$result = Hash::remove($result, 'Block');

		//期待値の生成
		$expected = array();

		//テスト実施
		$this->_assertArray($expected, $result);
	}

}
