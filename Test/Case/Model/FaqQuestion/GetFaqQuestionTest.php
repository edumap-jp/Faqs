<?php
/**
 * FaqQuestion::getFaqQuestion()のテスト
 *
 * @property FaqQuestion $FaqQuestion
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowGetTest', 'Workflow.TestSuite');

/**
 * FaqQuestion::getFaqQuestion()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\FaqQuestion
 */
class FaqQuestionGetFaqQuestionTest extends WorkflowGetTest {

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
		'plugin.faqs.block_setting_for_faq',
		'plugin.faqs.faq_question',
		'plugin.faqs.faq_question_order',
	);

/**
 * Model name
 *
 * @var array
 */
	protected $_modelName = 'FaqQuestion';

/**
 * Method name
 *
 * @var array
 */
	protected $_methodName = 'getFaqQuestion';

/**
 * Default expected data
 *
 * @var array
 */
	private $__defaultExpected = array(
		'FaqQuestion' => array(
			'id' => '1',
			'faq_id' => '2',
			'block_id' => '2',
			'key' => 'faq_question_1',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '1',
			'is_active' => true,
			'is_latest' => false,
			'question' => 'Question value',
			'answer' => 'Answer value',
		),
		'FaqQuestionOrder' => array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'faq_question_key' => 'faq_question_1',
			'weight' => '1',
		),
		'Faq' => array(
			'id' => '2',
			'key' => 'faq_1',
			'block_id' => '2',
			'name' => 'Faq name 1',
			'language_id' => '2',
		),
		'Category' => array(
			'id' => '1',
			'block_id' => '2',
			'key' => 'category_1',
			'name' => 'Category 1',
			'language_id' => '2',
		),
		'Block' => array(
			'id' => '2',
			'language_id' => '2',
			'room_id' => '2',
			'plugin_key' => 'faqs',
			'key' => 'block_1',
			'name' => 'Block name 1',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
			'content_count' => '0',
		),
	);

/**
 * getFaqQuestionのテスト
 *
 * @param array $expected 期待値
 * @param int $faqId
 * @param string $faqQuestionKey
 * @param array $conditions
 * @dataProvider dataProviderGetFaqQuestion
 *
 * @return void
 */
	public function testGetFaqQuestion($expected, $faqId, $faqQuestionKey, $conditions = []) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		$this->$model->Behaviors->unload('Likes.Like');
		$this->$model->unbindModel(
			array('belongsTo' => ['Like', 'LikesUser']), false
		);

		//テスト実行
		$result = $this->$model->$method($faqId, $faqQuestionKey, $conditions);

		//チェック
		$this->_assertArray($expected, $result);
	}

/**
 * getFaqQuestionのDataProvider
 *
 * #### 戻り値
 *  - array $expected 期待値
 *  - int $faqId 取得データのキー
 *  - string $faqQuestionKey 取得データのキー
 *  - array $conditions find conditions
 *
 * @return array
 */
	public function dataProviderGetFaqQuestion() {
		//存在しない
		$expected1 = array();
		$faqId1 = '1';
		$faqQuestionKey1 = 'faq_question_1';

		//存在(isActive)
		$expected2 = Hash::merge($this->__defaultExpected, array());
		$faqId2 = '2';
		$faqQuestionKey2 = 'faq_question_1';
		$condition2 = ['FaqQuestion.is_active' => true];

		//存在(isLatest)
		$expected3 = Hash::merge($this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => '2',
				'status' => '4',
				'is_active' => false,
				'is_latest' => true,
				'question' => 'Question value 2',
				'answer' => 'Answer value 2',
			),
		));
		$faqId3 = '2';
		$faqQuestionKey3 = 'faq_question_1';
		$condition3 = ['FaqQuestion.is_latest' => true];

		return array(
			array($expected1, $faqId1, $faqQuestionKey1), // 存在しない
			array($expected2, $faqId2, $faqQuestionKey2, $condition2), // 存在(IsActive)
			array($expected3, $faqId3, $faqQuestionKey3, $condition3), // 存在(IsLatest)

		);
	}

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
			}
			if ($path >= 3) {
				$result = Hash::remove($result, '{n}.{n}.' . $field);
				$result = Hash::remove($result, '{n}.{s}.' . $field);
			}
		}
		$this->assertEquals($expected, $result);
	}

}
