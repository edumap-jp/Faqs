<?php
/**
 * FaqFrameSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * FaqFrameSettingFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Fixture
 * @codeCoverageIgnore
 */
class FaqFrameSettingFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'frame_key' => 'Lorem ipsum dolor sit amet',
			'content_per_page' => 1,
			'created_user' => 1,
			'created' => '2016-06-29 04:21:35',
			'modified_user' => 1,
			'modified' => '2016-06-29 04:21:35'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Faqs') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new FaqsSchema())->tables['faq_frame_settings'];

		parent::init();
	}

}
