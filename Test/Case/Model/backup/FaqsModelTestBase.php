<?php
/**
 * Common code for test of Faqs
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsBlockComponent', 'NetCommons.Controller/Component');
App::uses('NetCommonsRoomRoleComponent', 'NetCommons.Controller/Component');
App::uses('YACakeTestCase', 'NetCommons.TestSuite');
App::uses('AuthComponent', 'Component');
App::uses('Block', 'Blocks.Model');
App::uses('Faq', 'Faqs.Model');
App::uses('FaqSetting', 'Faqs.Model');
App::uses('FaqQuestion', 'Faqs.Model');
App::uses('FaqQuestionOrder', 'Faqs.Model');

/**
 * Common code for test of Faqs
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqsModelTestBase extends YACakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.workflow.comment',
		'plugin.faqs.faq',
		'plugin.faqs.faq_setting',
		'plugin.faqs.faq_question',
		'plugin.faqs.faq_question_order',
	);

/**
 * Test case of notBlank
 *
 * @var array
 */
	public $validateNotEmpty = array(
		null, '', false,
	);

/**
 * Test case of boolean
 *
 * @var array
 */
	public $validateBoolean = array(
		null, '', 'a', '99', 'false', 'true'
	);

/**
 * Test case of boolean
 *
 * @var array
 */
	public $validateNumber = array(
		null, '', 'abcde', false, true, '123abcd', 'false', 'true'
	);

/**
 * Do test assert, after created_date, created_user, modified_date and modified_user fields remove.
 *
 * @param array $expected expected data
 * @param array $result result data
 * @param int $path remove path
 * @param array $fields target fields
 * @return void
 */
	protected function _assertArray($expected, $result, $path = 3, $fields = ['created', 'created_user', 'modified', 'modified_user']) {
		foreach ($fields as $field) {
			if ($path >= 1) {
				$result = Hash::remove($result, $field);
			}
			if ($path >= 2) {
				$result = Hash::remove($result, '{n}.' . $field);
				$result = Hash::remove($result, '{s}.' . $field);
				if ($field === 'created_user') {
					$result = Hash::remove($result, 'TrackableCreator');
				}
				if ($field === 'modified_user') {
					$result = Hash::remove($result, 'TrackableUpdater');
				}
			}
			if ($path >= 3) {
				$result = Hash::remove($result, '{n}.{n}.' . $field);
				$result = Hash::remove($result, '{n}.{s}.' . $field);
				if ($field === 'created_user') {
					$result = Hash::remove($result, '{n}.TrackableCreator');
				}
				if ($field === 'modified_user') {
					$result = Hash::remove($result, '{n}.TrackableUpdater');
				}
			}
		}

		$this->assertEquals($expected, $result);
	}
}
