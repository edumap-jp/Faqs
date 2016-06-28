<?php
/**
 * FaqSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * FaqSettingFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Fixture
 */
class FaqSettingFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'use_workflow' => '1',
			'created_user' => '1',
			'created' => '2015-04-22 15:26:21',
			'modified_user' => '1',
			'modified' => '2015-04-22 15:26:21'
		),
		array(
			'id' => '2',
			'faq_key' => 'faq_2',
			'use_workflow' => '1',
			'created_user' => '1',
			'created' => '2015-04-22 15:26:21',
			'modified_user' => '1',
			'modified' => '2015-04-22 15:26:21'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Faqs') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new FaqsSchema())->tables['faq_settings'];

		parent::init();
	}

}
