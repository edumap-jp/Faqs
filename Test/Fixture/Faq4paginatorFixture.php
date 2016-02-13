<?php
/**
 * FaqFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqFixture', 'Faqs.Test/Fixture');

/**
 * FaqFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Fixture
 * @codeCoverageIgnore
 */
class Faq4paginatorFixture extends FaqFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Faq';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//FAQ 1
		array(
			'id' => '1',
			'block_id' => '1',
			'key' => 'faq_1',
			'name' => 'Faq name 1',
			'language_id' => '1',
		),
		array(
			'id' => '2',
			'block_id' => '2',
			'key' => 'faq_1',
			'name' => 'Faq name 1',
			'language_id' => '2',
		),
		//FAQ 2
		array(
			'id' => '3',
			'block_id' => '4',
			'key' => 'faq_2',
			'name' => 'Faq name 2',
			'language_id' => '2',
		),
		//FAQ 3
		array(
			'id' => '4',
			'block_id' => '6',
			'key' => 'faq_3',
			'name' => 'Faq name 2',
			'language_id' => '2',
		),

		//101-200まで、ページ遷移のためのテスト
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		for ($i = 101; $i <= 200; $i++) {
			$this->records[$i] = array(
				'id' => $i,
				'block_id' => $i,
				'key' => 'faq_' . $i,
				'name' => 'faq_name_' . $i,
				'language_id' => '2',
			);
		}
		parent::init();
	}

}
