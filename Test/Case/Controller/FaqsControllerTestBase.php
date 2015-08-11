<?php
/**
 * FaqsController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsFrameComponent', 'NetCommons.Controller/Component');
App::uses('NetCommonsBlockComponent', 'NetCommons.Controller/Component');
App::uses('NetCommonsRoomRoleComponent', 'NetCommons.Controller/Component');
App::uses('YAControllerTestCase', 'NetCommons.TestSuite');
App::uses('RolesControllerTest', 'Roles.Test/Case/Controller');
App::uses('AuthGeneralControllerTest', 'AuthGeneral.Test/Case/Controller');

/**
 * CategoriesController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller
 */
class FaqsControllerTestBase extends YAControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		//'plugin.blocks.block',
		//'plugin.blocks.block_role_permission',
		//'plugin.boxes.box',
		//'plugin.boxes.boxes_page',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.comments.comment',
		//'plugin.containers.container',
		//'plugin.containers.containers_page',
		'plugin.faqs.faq',
		'plugin.faqs.faq_setting',
		'plugin.faqs.faq_question',
		'plugin.faqs.faq_question_order',
		//'plugin.frames.frame',
		//'plugin.m17n.language',
		//'plugin.net_commons.site_setting',
		//'plugin.pages.languages_page',
		//'plugin.pages.page',
		//'plugin.pages.space',
		//'plugin.plugin_manager.plugin',
		//'plugin.plugin_manager.plugins_room',
		//'plugin.roles.role',
		//'plugin.roles.default_role_permission',
		//'plugin.rooms.roles_rooms_user',
		//'plugin.rooms.roles_room',
		//'plugin.rooms.room',
		//'plugin.rooms.room_role',
		//'plugin.rooms.room_role_permission',
		//'plugin.users.user',
		//'plugin.users.user_attributes_user',
	);

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		YACakeTestCase::loadTestPlugin($this, 'NetCommons', 'TestPlugin');

		Configure::write('Config.language', 'ja');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('Config.language', null);
		CakeSession::write('Auth.User', null);
		parent::tearDown();
	}
}
