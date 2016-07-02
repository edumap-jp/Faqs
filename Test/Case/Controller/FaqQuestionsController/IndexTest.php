<?php
/**
 * FaqQuestionsController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionsController', 'Faqs.Controller');
App::uses('WorkflowControllerIndexTest', 'Workflow.TestSuite');

/**
 * FaqQuestionsController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller\FaqQuestionsController
 */
class FaqQuestionsControllerIndexTest extends WorkflowControllerIndexTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.likes.like',
		'plugin.likes.likes_user',
		'plugin.workflow.workflow_comment',
		'plugin.faqs.faq',
		'plugin.faqs.faq_frame_setting',
		'plugin.faqs.faq_setting',
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
	protected $_controller = 'faq_questions';

/**
 * テストDataの取得
 *
 * @return array
 */
	private function __getData() {
		$frameId = '6';
		$blockId = '2';
		$blockKey = 'block_1';
		$faqId = '2';
		$faqKey = 'faq_1';
		$faqQuestionId = null;
		$faqQuestionKey = 'faq_question_3';

		$data = array(
			'Frame' => array(
				'id' => $frameId
			),
			'Block' => array(
				'id' => $blockId,
				'key' => $blockKey,
				'language_id' => '2',
				'room_id' => '1',
				'plugin_key' => $this->plugin,
			),
			'Faq' => array(
				'id' => $faqId,
				'key' => $faqKey,
			),
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'key' => $faqQuestionKey,
				'faq_id' => $faqId,
				'language_id' => '2',
				'category_id' => '2',
				'status' => null,
				'question' => 'Modify question',
				'answer' => 'Modify answer',
			),
		);

		return $data;
	}

/**
 * indexアクションのテスト(ログインなし)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderIndex() {
		$data = $this->__getData();
		$results = array();

		//ログインなし(カテゴリ指定なし)
		$results[0] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		//ログインなし(カテゴリ指定あり)
		$results[1] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'category_id' => 1),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		//チェック
		//--追加ボタンチェック(なし)
		$results[2] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertActionLink', 'action' => 'add', 'linkExist' => false, 'url' => array()),
		);

		return $results;
	}

/**
 * indexアクションのテスト(編集権限あり)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderIndexByEditable() {
		$data = $this->__getData();
		$results = array();

		//編集権限あり
		$base = 0;
		$results[0] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		//チェック
		//--追加ボタンチェック
		array_push($results, Hash::merge($results[$base], array(
			'assert' => array('method' => 'assertActionLink', 'action' => 'add', 'linkExist' => true, 'url' => array()),
		)));
		//--編集ボタンチェック
		array_push($results, Hash::merge($results[$base], array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
			'assert' => array('method' => 'assertActionLink', 'action' => 'edit', 'linkExist' => true, 'url' => array()),
		)));
		//フレームあり（ブロックなし）(コンテンツなし)テスト
		array_push($results, Hash::merge($results[$base], array(
			'urlOptions' => array('frame_id' => '14', 'block_id' => null),
			'assert' => array('method' => 'assertEquals', 'expected' => 'emptyRender'),
			'exception' => null, 'return' => 'viewFile'
		)));
		//フレームID指定なしテスト
		array_push($results, Hash::merge($results[$base], array(
			'urlOptions' => array('frame_id' => null, 'block_id' => $data['Block']['id']),
		)));

		return $results;
	}

/**
 * indexアクションのテスト(作成権限のみ)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderIndexByCreatable() {
		$data = $this->__getData();
		$results = array();

		//作成権限あり
		$base = 0;
		$results[0] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		//チェック
		//--追加ボタンチェック
		array_push($results, Hash::merge($results[$base], array(
			'assert' => array('method' => 'assertActionLink', 'action' => 'add', 'linkExist' => true, 'url' => array()),
		)));
		//--編集ボタンチェック
		array_push($results, Hash::merge($results[$base], array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
			'assert' => array('method' => 'assertActionLink', 'action' => 'edit', 'linkExist' => true, 'url' => array()),
		)));
		//フレームID指定なしテスト
		array_push($results, Hash::merge($results[$base], array(
			'urlOptions' => array('frame_id' => null, 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		)));

		return $results;
	}

/**
 * index()の順序変更ボタン（ロールごと）テスト
 *
 * @param string $role ロール名
 * @param bool $isException Exceptionの有無
 * @dataProvider dataProviderIndexOrders
 * @return void
 */
	public function testIndexOrders($role, $isException) {
		//ログイン
		if (isset($role)) {
			TestAuthGeneral::login($this, $role);
		}
		//テスト実施
		$frameId = '6';
		$url = array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'index',
			'frame_id' => $frameId
		);
		$result = $this->_testNcAction($url, array('method' => 'get'));

		//チェック
		//--順序変更ボタンチェック
		$blockId = '2';
		$editLink = $url;
		$editLink['controller'] = 'faq_question_orders';
		$editLink['action'] = 'edit';
		$editLink['block_id'] = $blockId;
		if ($isException === true) {
			$this->assertRegExp(
				'/<a href=".*?' . preg_quote(NetCommonsUrl::actionUrl($editLink), '/') . '.*?".*?>/', $result
			);
		} else {
			$this->assertNotRegExp(
				'/<a href=".*?' . preg_quote(NetCommonsUrl::actionUrl($editLink), '/') . '.*?".*?>/', $result
			);
		}

		//ログアウト
		TestAuthGeneral::logout($this);
	}

/**
 * index順序変更ボタン(ロールごと)チェックDataProvider
 *
 * ### 戻り値
 *  - role: ロール
 *  - isException: Exceptionの有無
 *
 * @return array
 */
	public function dataProviderIndexOrders() {
		$data = array(
			array(Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR, true),
			array(Role::ROOM_ROLE_KEY_CHIEF_EDITOR, true),
			array(Role::ROOM_ROLE_KEY_EDITOR, true),
			array(Role::ROOM_ROLE_KEY_GENERAL_USER, false),
			array(Role::ROOM_ROLE_KEY_VISITOR, false),
			array(null, false),
		);
		return $data;
	}

}
