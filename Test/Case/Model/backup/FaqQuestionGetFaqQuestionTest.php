<?php
/**
 * Test of FaqQuestion->getFaqQuestion()
 *
 * @property FaqQuestion $FaqQuestion
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionTestBase', 'Faqs.Test/Case/Model');

/**
 * Test of FaqQuestion->getFaqQuestion()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqQuestionGetFaqQuestionTest extends FaqQuestionTestBase {

/**
 * Default expected data
 *
 * @var array
 */
	private $__defaultExpected = array(
		'FaqQuestion' => array(
			'id' => '1',
			'faq_id' => '1',
			'key' => 'faq_question_1',
			'language_id' => '2',
			'category_id' => '100',
			'status' => '1',
			'is_active' => true,
			'is_latest' => true,
			'question' => 'Question value',
			'answer' => 'Answer value',
		),
		'FaqQuestionOrder' => array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'faq_question_key' => 'faq_question_1',
			'weight' => '1',
		),
		'Faq' => array(
			'id' => '1',
			'key' => 'faq_1',
			'block_id' => '100',
			'name' => 'faq name 100',
			'is_auto_translated' => false,
			'translation_engine' => null,
		),
		'Category' => array(
			'id' => '100',
			'block_id' => '100',
			'key' => 'category_100',
			'name' => 'category name 100',
		),
		'CategoryOrder' => array(
			'id' => '100',
			'category_key' => 'category_100',
			'block_key' => 'block_100',
			'weight' => '1',
		),
	);

/**
 * Expect to get the FaqQuestion
 *
 * @return void
 */
	public function test() {
		//データ生成
		$faqId = '1';
		$faqQuestionKey = 'faq_question_1';

		//処理実行
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey);

		//期待値の生成
		$expected = Hash::merge($this->__defaultExpected, array());

		//テスト実施
		$this->_assertArray($expected, $result);
	}

/**
 * Expect to get by isActive on conditions
 *
 * @return void
 */
	public function testByIsActive() {
		//データ生成
		$faqId = '1';
		$faqQuestionKey = 'faq_question_2';

		//処理実行
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, ['FaqQuestion.is_active' => true]);

		//期待値の生成
		$expected = Hash::merge($this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => '2',
				'key' => 'faq_question_2',
				'status' => '1',
				'is_active' => true,
				'is_latest' => false,
				'question' => 'Question value 2',
				'answer' => 'Answer value 2',
			),
			'FaqQuestionOrder' => array(
				'id' => '2',
				'faq_question_key' => 'faq_question_2',
				'weight' => '2',
			),
		));

		//テスト実施
		$this->_assertArray($expected, $result);
	}

/**
 * Expect to get by isLatest on conditions
 *
 * @return void
 */
	public function testByIsLatest() {
		//データ生成
		$faqId = '1';
		$faqQuestionKey = 'faq_question_2';

		//処理実行
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, ['FaqQuestion.is_latest' => true]);

		//期待値の生成
		$expected = Hash::merge($this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => '3',
				'key' => 'faq_question_2',
				'status' => '3',
				'is_active' => false,
				'is_latest' => true,
				'question' => 'Question value 3',
				'answer' => 'Answer value 3',
			),
			'FaqQuestionOrder' => array(
				'id' => '2',
				'faq_question_key' => 'faq_question_2',
				'weight' => '2',
			),
		));

		//テスト実施
		$this->_assertArray($expected, $result);
	}

}
