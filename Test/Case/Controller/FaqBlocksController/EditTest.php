<?php
/**
 * FaqBlocksController Test Case
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqBlocksController', 'Faqs.Controller');
App::uses('BlocksControllerEditTest', 'Blocks.TestSuite');

/**
 * FaqBlocksController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Controller
 */
class FaqBlocksControllerEditTest extends BlocksControllerEditTest {

/**
 * Plugin name
 *
 * @var array
 */
	public $plugin = 'faqs';

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
		'plugin.faqs.faq_setting',
		'plugin.faqs.faq_question',
		'plugin.faqs.faq_question_order',
	);

/**
 * テストDataの取得
 *
 * @param bool $isEdit 編集かどうか
 * @return array
 */
	private function __getData($isEdit) {
		$frameId = '6';

		if ($isEdit) {
			$blockId = '4';
			$blockKey = 'block_2';
			$faqId = '3';
			$faqKey = 'faq_2';
			$faqSettingId = '2';
		} else {
			$blockId = null;
			$blockKey = null;
			$faqId = null;
			$faqKey = null;
			$faqSettingId = null;
		}

		$data = array(
			'save_' . WorkflowComponent::STATUS_PUBLISHED => null,
			'Frame' => array(
				'id' => $frameId
			),
			'Block' => array(
				'id' => $blockId,
				'key' => $blockKey,
				'language_id' => '2',
				'room_id' => '1',
				'plugin_key' => $this->plugin,
				'public_type' => '1',
				'from' => null,
				'to' => null,
			),
			'Faq' => array(
				'id' => $faqId,
				'key' => $faqKey,
				'block_id' => $blockId,
				'name' => 'Faq name'
			),
			'FaqSetting' => array(
				'id' => $faqSettingId,
				'faq_key' => $faqKey,
			),
			'WorkflowComment' => array(
				'comment' => 'WorkflowComment save test'
			),
		);

		return $data;
	}

/**
 * add()アクションDataProvider
 *
 * ### 戻り値
 *  - method: リクエストメソッド（get or post or put）
 *  - data: 登録データ
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderAdd() {
		$data = $this->__getData(false);

		$results = array();
		$results[0] = array('method' => 'get');
		$results[1] = array('method' => 'put');
		$results[2] = array('method' => 'post', 'data' => $data, 'validationError' => false);
		$results[3] = array('method' => 'post', 'data' => $data,
			'validationError' => array(
				'field' => 'Faq.name',
				'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'FAQ Name'))
			)
		);

		return $results;
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - method: リクエストメソッド（get or post or put）
 *  - data: 登録データ
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderEdit() {
		$data = $this->__getData(true);

		$results = array();
		$results[0] = array('method' => 'get');
		$results[1] = array('method' => 'post');
		$results[2] = array('method' => 'put', 'data' => $data, 'validationError' => false);
		$results[3] = array('method' => 'put', 'data' => $data,
			'validationError' => array(
				'field' => 'Faq.name',
				'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'FAQ Name'))
			)
		);

		return $results;
	}

/**
 * edit()のテスト(ExceptionError)
 *
 * @param array $urlOptions URLオプション
 * @param array $assert テストの期待値
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @dataProvider dataProviderEditExceptionError
 * @return void
 */
	public function testEditExceptionError($urlOptions, $assert, $exception = null, $return = 'view') {
		//ログイン
		TestAuthGeneral::login($this);

		if ($exception) {
			$this->_mockForReturnFalse('Faqs.Faq', 'getFaq');
		}

		$url = Hash::merge(array(
			'plugin' => $this->plugin,
			'controller' => $this->_controller,
			'action' => 'edit',
		), $urlOptions);

		$this->_testGetAction($url, $assert, $exception, $return);

		//チェック
		//$this->asserts($asserts, $this->contents);

		//ログアウト
		TestAuthGeneral::logout($this);
	}

/**
 * editError()アクションDataProvider
 *
 * ### 戻り値
 *  - method: リクエストメソッド（get or post or put）
 *  - data: 登録データ
 * -  urlOptions: URLオプション
 * -  assert: テストの期待値
 * -  exception: Exception
 * -  return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditExceptionError() {
		$data = $this->__getData(true);

		$results = array();
		$results[0] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
			'exception' => 'BadRequestException',
		);

		$results[0] = array(
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
			'exception' => 'BadRequestException',
			'return' => 'json'
		);

		return $results;
	}

/**
 * delete()アクションDataProvider
 *
 * ### 戻り値
 *  - data 削除データ
 *
 * @return array
 */
	public function dataProviderDelete() {
		$blockId = '2';

		$data = array(
			'Block' => array(
				'id' => $blockId,
				'key' => 'block_2',
			),
			'Faq' => array(
				'key' => 'faq_key_1',
			),
		);

		return array(
			array('data' => $data)
		);
	}

}
