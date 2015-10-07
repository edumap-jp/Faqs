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
App::uses('WorkflowControllerDeleteTest', 'Workflow.TestSuite');

/**
 * FaqQuestionsController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\FaqQuestions\Test\Case\Controller
 */
class FaqQuestionsControllerDeleteTest extends WorkflowControllerDeleteTest {

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
 * @param string $faqQuestionKey 質問ID
 * @return array
 */
	private function __getData($faqQuestionKey = 'faq_question_1') {
		$frameId = '6';
		$blockId = '2';
		$faqId = '2';
		$faqKey = 'faq_1';
		if ($faqQuestionKey === 'faq_question_2') {
			$faqQuestionId = '3';
		} elseif ($faqQuestionKey === 'faq_question_4') {
			$faqQuestionId = '5';
		} else {
			$faqQuestionId = '2';
		}

		$data = array(
			'delete' => null,
			'Frame' => array(
				'id' => $frameId
			),
			'Block' => array(
				'id' => $blockId,
			),
			'Faq' => array(
				'key' => $faqKey,
			),
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'key' => $faqQuestionKey,
				'faq_id' => $faqId,
			),
		);

		return $data;
	}

/**
 * deleteアクションのGETテスト用DataProvider
 *
 * ### 戻り値
 *  - role: ロール
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderDeleteGet() {
		$data = $this->__getData();
		$results = array();

		$results[0] = array('role' => null,
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
			'assert' => null, 'exception' => 'ForbiddenException'
		);
		array_push($results, Hash::merge($results[0], array(
			'role' => Role::ROOM_ROLE_KEY_GENERAL_USER,
			'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => 'faq_question_2'),
			'assert' => null, 'exception' => 'BadRequestException'
		)));
		array_push($results, Hash::merge($results[0], array(
			'role' => Role::ROOM_ROLE_KEY_EDITOR,
			'assert' => null, 'exception' => 'BadRequestException'
		)));
		array_push($results, Hash::merge($results[0], array(
			'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
			'assert' => null, 'exception' => 'BadRequestException'
		)));
		array_push($results, Hash::merge($results[0], array(
			'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
			'assert' => null, 'exception' => 'BadRequestException', 'return' => 'json'
		)));

		return $results;
	}

/**
 * deleteアクションのPOSTテスト用DataProvider
 *
 * ### 戻り値
 *  - data: 登録データ
 *  - role: ロール
 *  - urlOptions: URLオプション
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderDeletePost() {
		$data = $this->__getData();

		return array(
			//ログインなし
			array(
				'data' => $data, 'role' => null,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
				'exception' => 'ForbiddenException'
			),
			//作成権限のみ
			//--他人の記事
			array(
				'data' => $data, 'role' => Role::ROOM_ROLE_KEY_GENERAL_USER,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
				'exception' => 'BadRequestException'
			),
			array(
				'data' => $data, 'role' => Role::ROOM_ROLE_KEY_GENERAL_USER,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
				'exception' => 'BadRequestException', 'return' => 'json'
			),
			//--自分の記事＆一度も公開されていない
			array(
				'data' => $this->__getData('faq_question_2'), 'role' => Role::ROOM_ROLE_KEY_GENERAL_USER,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => 'faq_question_2'),
			),
			//--自分の記事＆一度公開している
			array(
				'data' => $this->__getData('faq_question_4'), 'role' => Role::ROOM_ROLE_KEY_GENERAL_USER,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => 'faq_question_4'),
				'exception' => 'BadRequestException'
			),
			//編集権限あり
			//--公開していない
			array(
				'data' => $this->__getData('faq_question_2'), 'role' => Role::ROOM_ROLE_KEY_EDITOR,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => 'faq_question_2'),
			),
			//--公開している
			array(
				'data' => $this->__getData('faq_question_4'), 'role' => Role::ROOM_ROLE_KEY_EDITOR,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => 'faq_question_4'),
				'exception' => 'BadRequestException'
			),
			//公開権限あり
			//フレーム削除テスト
			array(
				'data' => $data, 'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
				'urlOptions' => array('frame_id' => '12', 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
			),
			//フレームなしテスト
			array(
				'data' => $data, 'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
				'urlOptions' => array('frame_id' => '999999', 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
			),
			//フレームID指定なしテスト
			array(
				'data' => $data, 'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
				'urlOptions' => array('frame_id' => null, 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
			),
		);
	}

/**
 * deleteアクションのExceptionErrorテスト用DataProvider
 *
 * ### 戻り値
 *  - mockModel: Mockのモデル
 *  - mockMethod: Mockのメソッド
 *  - data: 登録データ
 *  - urlOptions: URLオプション
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderDeleteExceptionError() {
		$data = $this->__getData();

		return array(
			array(
				'mockModel' => 'Faqs.FaqQuestion', 'mockMethod' => 'deleteFaqQuestion', 'data' => $data,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
				'exception' => 'BadRequestException'
			),
			array(
				'mockModel' => 'Faqs.FaqQuestion', 'mockMethod' => 'deleteFaqQuestion', 'data' => $data,
				'urlOptions' => array('frame_id' => $data['Frame']['id'], 'block_id' => $data['Block']['id'], 'key' => $data['FaqQuestion']['key']),
				'exception' => 'BadRequestException', 'return' => 'json'
			),
		);
	}

}
