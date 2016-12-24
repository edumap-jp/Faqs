<?php
/**
 * FaqQuestionOrdersController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionOrdersController', 'Faqs.Controller');
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * FaqQuestionOrdersController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller
 */
class FaqQuestionOrdersControllerEditTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.categories.categories_language',
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
	protected $_controller = 'faq_question_orders';

/**
 * テストDataの取得
 *
 * @param string $role ロール
 * @return array
 */
	private function __getData() {
		$frameId = '6';
		$blockId = '2';
		$blockKey = 'block_1';
		$faqId = '2';
		$faqKey = 'faq_1';

		$data = array(
			'Frame' => array(
				'id' => $frameId
			),
			'Block' => array(
				'id' => $blockId,
				'key' => $blockKey,
				'language_id' => '2',
				'room_id' => '2',
				'plugin_key' => $this->plugin,
			),
			'Faq' => array(
				'id' => $faqId,
				'key' => $faqKey,
			),
			'FaqQuestions' => array(
				0 => array(
					'FaqQuestionOrder' => array(
						'id' => 1,
						'faq_key' => $faqKey,
						'faq_question_key' => 'faq_question_1',
						'weight' => 2,
					),
				),
				1 => array(
					'FaqQuestionOrder' => array(
						'id' => '2',
						'faq_key' => $faqKey,
						'faq_question_key' => 'faq_question_2',
						'weight' => 3,
					),
				),
			),
		);

		return $data;
	}

/**
 * editアクションのGETテスト
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderEditGet
 * @return void
 */
	public function testEditGet($urlOptions, $assert, $exception = null, $return = 'view') {
		//Exception
		if ($exception) {
			$this->setExpectedException($exception);
		}

		//テスト実施
		$url = Hash::merge(array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'edit',
		), $urlOptions);

		$this->_testGetAction($url, $assert, $exception, $return);
	}

/**
 * editアクションのGETテスト(ログインなし)用DataProvider
 *
 * #### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditGet() {
		$data = $this->__getData();
		$results = array();

		//ログインなし
		$results[0] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => null, 'exception' => 'ForbiddenException'
		);
		return $results;
	}

/**
 * editアクションのGETテスト
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderEditGetByPublishable
 * @return void
 */
	public function testEditGetByPublishable($urlOptions, $assert, $exception = null, $return = 'view') {
		//ログイン
		TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR);

		if ($exception) {
			if (isset($urlOptions['block_id'])) { // Faqなし
				$mock = $this->getMockForModel('Faqs.Faq', array('getFaq'));
				$mock->expects($this->once())
					->method('getFaq')
					->will($this->returnValue(false));
			} else { // Blockなし
			}
		}

		//テスト実施
		$url = Hash::merge(array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'edit',
		), $urlOptions);

		$this->_testGetAction($url, $assert, $exception, $return);

		//ログアウト
		TestAuthGeneral::logout($this);
	}

/**
 * editアクションのGETテスト(ログインあり)用DataProvider
 *
 * #### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditGetByPublishable() {
		$data = $this->__getData();
		$results = array();

		//正常
		$results[0] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		);

		//ExceptionError(Faqなし)
		$results[1] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
			'exception' => 'BadRequestException',
			'return' => 'json'
		);

		//Blockなし
		$results[2] = array(
			'urlOptions' => array('frame_id' => '16'),
			'assert' => array('method' => 'assertEquals', 'expected' => 'emptyRender'),
			'exception' => null, 'return' => 'viewFile'
		);

		//フレーム削除テスト
		$results[3] = array(
			'urlOptions' => array('frame_id' => '12', 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		);

		//フレームIDなし(不正)テスト
		$results[4] = array(
			'urlOptions' => array('frame_id' => '999999', 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		);

		//フレームID指定なしテスト
		$results[5] = array(
			'urlOptions' => array('frame_id' => null, 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		);

		return $results;
	}

/**
 * editアクションのPOSTテスト
 *
 * @param array $data POSTデータ
 * @param string $role ロール
 * @param array $urlOptions URLオプション
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderEditPost
 * @return void
 */
	public function testEditPost($data, $role, $urlOptions, $exception = null, $return = 'view') {
		//ログイン
		if (isset($role)) {
			TestAuthGeneral::login($this, $role);
		}

		//テスト実施
		$this->_testPostAction('post', $data, Hash::merge(array('action' => 'edit'), $urlOptions), $exception, $return);

		//正常の場合、リダイレクト
		if (! $exception) {
			$header = $this->controller->response->header();
			$this->assertNotEmpty($header['Location']);
		}

		//ログアウト
		if (isset($role)) {
			TestAuthGeneral::logout($this);
		}
	}

/**
 * editアクションのPOSTテスト用DataProvider
 *
 * #### 戻り値
 *  - data: 登録データ
 *  - role: ロール
 *  - urlOptions: URLオプション
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditPost() {
		$data = $this->__getData();

		return array(
			//ログインなし
			array(
				'data' => $data, 'role' => null,
				'urlOptions' => array('frame_id' => $data['Frame']['id']),
				'exception' => 'ForbiddenException'
			),
			//正常
			array(
				'data' => $data, 'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
				'urlOptions' => array('frame_id' => $data['Frame']['id']),
			),
			//フレームID指定なしテスト
			array(
				'data' => $data, 'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
				'urlOptions' => array('frame_id' => null),
			),

		);
	}

/**
 * editアクションのValidateionErrorテスト
 *
 * @param array $data POSTデータ
 * @param array $urlOptions URLオプション
 * @param string|null $validationError ValidationError
 * @dataProvider dataProviderEditValidationError
 * @return void
 */
	public function testEditValidationError($data, $urlOptions, $validationError = null) {
		//ログイン
		TestAuthGeneral::login($this);

		//データ準備
		$dataOrder = $data['FaqQuestions'][0];
		$data['FaqQuestions'][0] = Hash::remove($dataOrder, $validationError['field']);
		$data['FaqQuestions'][0] = Hash::insert($dataOrder, $validationError['field'], $validationError['value']);

		//テスト実施
		$url = Hash::merge(array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'edit',
		), $urlOptions);
		$result = $this->_testNcAction($url, array('method' => 'post', 'data' => $data));

		//バリデーションエラー
		$asserts = array(
			array('method' => 'assertNotEmpty', 'value' => $this->controller->validationErrors),
			array('method' => 'assertTextContains', 'expected' => $validationError['message']),
		);

		//チェック
		$this->asserts($asserts, $result);

		//ログアウト
		TestAuthGeneral::logout($this);
	}

/**
 * editアクションのValidationErrorテスト用DataProvider
 *
 * #### 戻り値
 *  - data: 登録データ
 *  - urlOptions: URLオプション
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderEditValidationError() {
		$data = $this->__getData();

		$result = array(
			'data' => $data,
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
		);

		return array(
			//バリデーションエラー
			Hash::merge($result, array(
				'validationError' => array(
					'field' => 'FaqQuestionOrder.faq_question_key',
					'value' => '',
					'message' => __d('net_commons', 'Not found.'), //「登録されていません。
				)
			)),
		);
	}

}
