<?php
/**
 * FaqQuestionFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * FaqQuestionFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Fixture
 */
class FaqQuestionFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//FAQ 1  日本語
		//質問1
		array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'block_id' => '2',
			'key' => 'faq_question_1',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '1',
			'is_active' => true,
			'is_latest' => false,
			'question' => 'Question value',
			'answer' => 'Answer value',
			'created_user' => '1'
		),
		array(
			'id' => '2',
			'faq_key' => 'faq_1',
			'block_id' => '2',
			'key' => 'faq_question_1',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '4',
			'is_active' => false,
			'is_latest' => true,
			'question' => 'Question value 2',
			'answer' => 'Answer value 2',
			'created_user' => '1'
		),
		//質問2(一般が書いた質問＆一度も公開していない)
		array(
			'id' => '3',
			'faq_key' => 'faq_1',
			'block_id' => '2',
			'key' => 'faq_question_2',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,
			'question' => 'Question value 3',
			'answer' => 'Answer value 3',
			'created_user' => '4'
		),
		//質問3
		array(
			'id' => '4',
			'faq_key' => 'faq_1',
			'block_id' => '2',
			'key' => 'faq_question_3',
			'language_id' => '2',
			'category_id' => null,
			'status' => '1',
			'is_active' => true,
			'is_latest' => true,
			'question' => 'Question value 4',
			'answer' => 'Answer value 4',
			'created_user' => '4'
		),
		//質問4(一般が書いた質問＆一度公開している)
		array(
			'id' => '5',
			'faq_key' => 'faq_1',
			'block_id' => '2',
			'key' => 'faq_question_4',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '1',
			'is_active' => true,
			'is_latest' => true,
			'question' => 'Question value 5',
			'answer' => 'Answer value 5',
			'created_user' => '4'
		),
		array(
			'id' => '6',
			'faq_key' => 'faq_1',
			'block_id' => '2',
			'key' => 'faq_question_4',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,
			'question' => 'Question value 6',
			'answer' => 'Answer value 6',
			'created_user' => '4'
		),
		//質問5(chief_userが書いた質問＆一度も公開していない)
		array(
			'id' => '7',
			'faq_key' => 'faq_1',
			'block_id' => '2',
			'key' => 'faq_question_5',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,
			'question' => 'Question value 3',
			'answer' => 'Answer value 3',
			'created_user' => '3'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Faqs') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new FaqsSchema())->tables['faq_questions'];

		parent::init();
	}

}
