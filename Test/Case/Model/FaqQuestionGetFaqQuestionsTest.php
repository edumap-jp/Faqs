<?php
/**
 * Test of FaqQuestion->getFaqQuestions()
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
 * Test of FaqQuestion->getFaqQuestions()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqQuestionGetFaqQuestionsTest extends FaqQuestionTestBase {

/**
 * Default expected questions data
 *
 * @var array
 */
	//private $__defaultExpectedQuestions = array(
	//	0 => array(
	//		'FaqQuestion' => array(
	//			'id' => '1',
	//			'faq_id' => '1',
	//			'key' => 'faq_question_1',
	//			'language_id' => '2',
	//			'category_id' => '100',
	//			'status' => '1',
	//			'is_active' => '1',
	//			'is_latest' => '1',
	//			'question' => 'Question value',
	//			'answer' => 'Answer value',
	//		),
	//		'FaqQuestionOrder' => array(
	//			'id' => '1',
	//			'faq_key' => 'faq_1',
	//			'faq_question_key' => 'faq_question_1',
	//			'weight' => '1',
	//		),
	//	),
	//	1 => array(
	//		'FaqQuestion' => array(
	//			'id' => '2',
	//			'faq_id' => '1',
	//			'key' => 'faq_question_2',
	//			'language_id' => '2',
	//			'category_id' => '100',
	//			'status' => '1',
	//			'is_active' => '1',
	//			'is_latest' => '',
	//			'question' => 'Question value 2',
	//			'answer' => 'Answer value 2',
	//		),
	//		'FaqQuestionOrder' => array(
	//			'id' => '2',
	//			'faq_key' => 'faq_1',
	//			'faq_question_key' => 'faq_question_2',
	//			'weight' => '2',
	//		),
	//	),
	//	2 => array(
	//		'FaqQuestion' => array(
	//			'id' => '4',
	//			'faq_id' => '1',
	//			'key' => 'faq_question_3',
	//			'language_id' => '2',
	//			'category_id' => '',
	//			'status' => '1',
	//			'is_active' => '1',
	//			'is_latest' => '1',
	//			'question' => 'Question value 4',
	//			'answer' => 'Answer value 4',
	//		),
	//		'FaqQuestionOrder' => array(
	//			'id' => '3',
	//			'faq_key' => 'faq_1',
	//			'faq_question_key' => 'faq_question_3',
	//			'weight' => '3',
	//		),
	//	)
	//);

/**
 * Default expected category data
 *
 * @var array
 */
	//private $__defaultExpectedCategory = array(
	//	'Category' => array(
	//		'id' => '100',
	//		'block_id' => '100',
	//		'key' => 'category_100',
	//		'name' => 'category name 100',
	//	),
	//	'CategoryOrder' => array(
	//		'id' => '100',
	//		'category_key' => 'category_100',
	//		'block_key' => 'block_100',
	//		'weight' => '1',
	//	)
	//);

/**
 * Expect to get the FaqQuestions
 *
 * @return void
 */
	public function test() {
		////データ生成
		//$faqId = '1';
		//$conditions = array(
		//	'FaqQuestion.faq_id' => $faqId,
		//	'FaqQuestion.is_active' => true,
		//);
		//
		////処理実行
		//$result = $this->FaqQuestion->getFaqQuestions($conditions);
		//$result = Hash::remove($result, '{n}.Faq');
		////CakeLog::debug(print_r($result, true));
		//
		////期待値の生成
		//$expected = Hash::merge($this->__defaultExpectedQuestions, array());
		//$expected[0] = Hash::merge($expected[0], $this->__defaultExpectedCategory);
		//$expected[1] = Hash::merge($expected[1], $this->__defaultExpectedCategory);
		//$expected[2] = Hash::merge($expected[2], array(
		//	'Category' => array(
		//		'id' => '',
		//		'block_id' => '',
		//		'key' => '',
		//		'name' => '',
		//	),
		//	'CategoryOrder' => array(
		//		'id' => '',
		//		'category_key' => '',
		//		'block_key' => '',
		//		'weight' => '',
		//	)
		//));
		//
		////テスト実施
		//$this->_assertArray($expected, $result);
	}

}
