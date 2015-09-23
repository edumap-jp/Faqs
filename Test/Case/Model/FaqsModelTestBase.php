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

//App::uses('NetCommonsBlockComponent', 'NetCommons.Controller/Component');
//App::uses('NetCommonsRoomRoleComponent', 'NetCommons.Controller/Component');
//App::uses('YACakeTestCase', 'NetCommons.TestSuite');
//App::uses('AuthComponent', 'Component');
//App::uses('Block', 'Blocks.Model');
//App::uses('Faq', 'Faqs.Model');
//App::uses('FaqSetting', 'Faqs.Model');
//App::uses('FaqQuestion', 'Faqs.Model');
//App::uses('FaqQuestionOrder', 'Faqs.Model');
App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * Common code for test of Faqs
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqsModelTestBase extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.comments.comment',
		'plugin.faqs.faq',
		'plugin.faqs.faq_setting',
		'plugin.faqs.faq_question',
		'plugin.faqs.faq_question_order',
	);

}
