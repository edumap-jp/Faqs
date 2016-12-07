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

App::uses('FaqQuestionsController', 'FaqQuestions.Controller');
App::uses('WorkflowControllerViewTest', 'Workflow.TestSuite');

/**
 * FaqQuestionsController Test Case
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\FaqQuestions\Test\Case\Controller
 */
class FaqQuestionsControllerViewTest extends WorkflowControllerViewTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.categories.categories_language',
		'plugin.likes.like',
		'plugin.likes.likes_user',
		'plugin.workflow.workflow_comment',
		'plugin.faqs.faq',
		'plugin.faqs.faq_frame_setting',
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
	protected $_controller = 'faq_questions';

/**
 * viewアクションのテスト用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderView() {
		$results = array();

		//ログインなし
		//--コンテンツあり
		$results[0] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '2', 'key' => 'faq_question_3'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[1] = Hash::merge($results[0], array(
			'assert' => array('method' => 'assertActionLink', 'action' => 'edit', 'linkExist' => false, 'url' => array()),
		));
		//--コンテンツなし
		$results[2] = array(
			'urlOptions' => array('frame_id' => '14', 'block_id' => null, 'key' => null),
			'assert' => array('method' => 'assertEquals', 'expected' => 'emptyRender'),
			'exception' => null, 'return' => 'viewFile'
		);

		return $results;
	}

/**
 * viewアクションのテスト(作成権限のみ)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderViewByCreatable() {
		$results = array();
		//作成権限のみ(一般が書いた質問＆一度公開している)
		$results[0] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '2', 'key' => 'faq_question_4'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[1] = Hash::merge($results[0], array(
			'assert' => array('method' => 'assertActionLink', 'action' => 'edit', 'linkExist' => true, 'url' => array()),
		));
		//作成権限のみ(一般が書いた質問＆公開前)
		$results[2] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '2', 'key' => 'faq_question_2'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[3] = Hash::merge($results[2], array(
			'assert' => array('method' => 'assertActionLink', 'action' => 'edit', 'linkExist' => true, 'url' => array()),
		));
		//作成権限のみ(他人が書いた質問＆公開中)
		$results[4] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '2', 'key' => 'faq_question_1'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[5] = Hash::merge($results[4], array(
			'assert' => array('method' => 'assertActionLink', 'action' => 'edit', 'linkExist' => false, 'url' => array()),
		));
		//作成権限のみ(他人が書いた質問＆公開前)
		$results[6] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '2', 'key' => 'faq_question_5'),
			'assert' => null,
			'exception' => 'BadRequestException',
		);
		$results[7] = Hash::merge($results[6], array(
			'exception' => 'BadRequestException', 'return' => 'json'
		));
		//--コンテンツなし
		$results[8] = array(
			'urlOptions' => array('frame_id' => '14', 'block_id' => null, 'key' => null),
			'assert' => array('method' => 'assertEquals', 'expected' => 'emptyRender'),
			'exception' => null, 'return' => 'viewFile'
		);
		//--パラメータ不正(keyに該当する質問が存在しない)
		$results[9] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '2', 'key' => 'faq_question_99'),
			'assert' => null,
			'exception' => 'BadRequestException',
		);

		//--FAQなし
		$results[10] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '12', 'key' => 'faq_xx'),
			'assert' => 'null',
			'exception' => 'BadRequestException',
		);
		$results[11] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '12', 'key' => 'faq_xx'),
			'assert' => 'null',
			'exception' => 'BadRequestException',
			'return' => 'json'
		);

		return $results;
	}

/**
 * viewアクションのテスト用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderViewByEditable() {
		$results = array();

		//編集権限あり
		//--コンテンツあり
		$results[0] = array(
			'urlOptions' => array('frame_id' => '6', 'block_id' => '2', 'key' => 'faq_question_3'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[1] = Hash::merge($results[0], array(
			'assert' => array('method' => 'assertActionLink', 'action' => 'edit', 'linkExist' => true, 'url' => array()),
		));
		//--コンテンツなし
		$results[2] = array(
			'urlOptions' => array('frame_id' => '14', 'block_id' => null, 'key' => null),
			'assert' => array('method' => 'assertEquals', 'expected' => 'emptyRender'),
			'exception' => null, 'return' => 'viewFile'
		);
		//フレームID指定なしテスト
		$results[3] = array(
			'urlOptions' => array('frame_id' => null, 'block_id' => '2', 'key' => 'faq_question_3'),
			'assert' => array('method' => 'assertNotEmpty'),
		);
		$results[4] = Hash::merge($results[3], array(
			'assert' => array('method' => 'assertActionLink', 'action' => 'edit', 'linkExist' => true, 'url' => array()),
		));

		return $results;
	}

}
