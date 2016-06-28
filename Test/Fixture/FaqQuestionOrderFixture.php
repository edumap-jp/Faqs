<?php
/**
 * FaqQuestionOrderFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * FaqQuestionOrderFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Fixture
 */
class FaqQuestionOrderFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'faq_question_key' => 'faq_question_1',
			'weight' => '1',
		),
		array(
			'id' => '2',
			'faq_key' => 'faq_1',
			'faq_question_key' => 'faq_question_2',
			'weight' => '2',
		),
		array(
			'id' => '3',
			'faq_key' => 'faq_1',
			'faq_question_key' => 'faq_question_3',
			'weight' => '3',
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Faqs') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new FaqsSchema())->tables['faq_question_orders'];

		parent::init();
	}

}
