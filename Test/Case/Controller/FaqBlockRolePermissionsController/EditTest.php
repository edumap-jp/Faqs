<?php
/**
 * BlockRolePermissionsController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqBlockRolePermissionsController', 'Faqs.Controller');
App::uses('BlockRolePermissionsControllerEditTest', 'Blocks.TestSuite');

/**
 * FaqBlockRolePermissionsController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller
 */
class FaqBlockRolePermissionsControllerEditTest extends BlockRolePermissionsControllerEditTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.workflow.workflow_comment',
		'plugin.faqs.faq',
		'plugin.faqs.block_setting_for_faq',
		'plugin.faqs.faq_question',
		'plugin.faqs.faq_question_order',
	);

/**
 * Plugin name
 *
 * @var array
 */
	public $plugin = 'faqs';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'faq_block_role_permissions';

/**
 * テストDataの取得
 *
 * @param bool $isPost POSTかどうか
 * @return array
 */
	private function __getData($isPost) {
		if ($isPost) {
			$data = array(
				'FaqSetting' => array(
					'id' => 2,
					'faq_key' => 'faq_2',
					'use_workflow' => true,
					//'use_comment_approval' => true,
					'approval_type' => true,
				)
			);
		} else {
			$data = array(
				'FaqSetting' => array(
					'use_workflow',
					//'use_comment_approval',
					'approval_type',
				)
			);
		}
		return $data;
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - approvalFields コンテンツ承認の利用有無のフィールド
 *  - exception Exception
 *  - return testActionの実行後の結果
 *
 * @return void
 */
	public function dataProviderEditGet() {
		return array(
			array('approvalFields' => $this->__getData(false))
		);
	}

/**
 * editアクションのGETテスト(Exceptionエラー)
 *
 * @param array $approvalFields コンテンツ承認の利用有無のフィールド
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderEditGet
 * @return void
 */

	public function testEditGetExceptionError($approvalFields, $exception = null, $return = 'view') {
		$this->_mockForReturnFalse('Faqs.Faq', 'getFaq');

		$exception = 'BadRequestException';
		$this->testEditGet($approvalFields, $exception, $return);
	}

/**
 * editアクションのGET(JSON)テスト(Exceptionエラー)
 *
 * @param array $approvalFields コンテンツ承認の利用有無のフィールド
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderEditGet
 * @return void
 */
	public function testEditGetJsonExceptionError($approvalFields, $exception = null, $return = 'view') {
		$this->_mockForReturnFalse('Faqs.Faq', 'getFaq');

		$exception = 'BadRequestException';
		$return = 'json';
		$this->testEditGet($approvalFields, $exception, $return);
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - data POSTデータ
 *  - exception Exception
 *  - return testActionの実行後の結果
 *
 * @return void
 */
	public function dataProviderEditPost() {
		return array(
			array('data' => $this->__getData(true))
		);
	}

/**
 * editアクションのPOSTテスト(Saveエラー)
 *
 * @param array $data POSTデータ
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderEditPost
 * @return void
 */
	public function testEditPostSaveError($data, $exception = null, $return = 'view') {
		$data['BlockRolePermission']['content_creatable'][Role::ROOM_ROLE_KEY_GENERAL_USER]['roles_room_id'] = 'aaaa';

		//テスト実施
		$result = $this->testEditPost($data, false, $return);

		$approvalFields = $this->__getData(false);
		$this->_assertEditGetPermission($approvalFields, $result);
	}

}
