<?php
/**
 * Test of FaqQuestion->validateFaqQuestion()
 *
 * @property FaqQuestion $FaqQuestion
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqQuestionTestBase', 'Faqs.Test/Case/Model');

/**
 * Test of FaqQuestion->validateFaqQuestion()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class FaqQuestionValidateFaqQuestionTest extends FaqQuestionTestBase {

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultData = array(
		'FaqQuestion' => array(
			'id' => '1',
			'faq_id' => '1',
			'key' => 'faq_question_1',
			'language_id' => '2',
			'category_id' => '1',
			//'status' => '1',
			'question' => 'Modify question',
			'answer' => 'Modify answer',
			'created_user' => '1',
		),
		'FaqQuestionOrder' => array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'faq_question_key' => 'faq_question_1',
		),
		'WorkflowComment' => array(
			'comment' => 'Add comment',
		)
	);

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FaqQuestion->FaqQuestionOrder = ClassRegistry::init('Faqs.FaqQuestionOrder');
		$this->FaqQuestion->WorkflowComment = ClassRegistry::init('Workflow.WorkflowComment');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FaqQuestion->FaqQuestionOrder);
		unset($this->FaqQuestion->WorkflowComment);
		parent::tearDown();
	}

/**
 * __assertValidationError
 *
 * @param string $field Field name
 * @param array $data Save data
 * @param array $expected Expected value
 * @return void
 */
	private function __assertValidationError($field, $data, $expected) {
		//初期処理
		$this->setUp();
		//validate処理実行
		$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		//戻り値チェック
		$expectMessage = 'Expect `' . $field . '` field, error data: ' . print_r($data, true);
		$this->assertFalse($result, $expectMessage);
		//validationErrorsチェック
		$this->assertEquals($this->FaqQuestion->validationErrors, $expected);
		//終了処理
		$this->tearDown();
	}

/**
 * Expect to save as content published by user with content_publishable privilege
 *
 * @return void
 */
	public function testPublishedByContentPublishable() {
		////公開権限セット
		//$this->FaqQuestion->Behaviors->attach('Publishable');
		//$this->FaqQuestion->Behaviors->Publishable->setup($this->FaqQuestion, ['contentPublishable' => true]);
		//
		////データ生成
		//$data = Hash::merge($this->__defaultData, array(
		//	'FaqQuestion' => array(
		//		'status' => WorkflowComponent::STATUS_PUBLISHED
		//	)
		//));
		//unset($data['FaqQuestion']['id']);
		//
		////処理実行
		//$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		//$this->assertTrue($result);
	}

/**
 * Expect to save as content published by user without content_publishable privilege
 *
 * @return void
 */
	public function testPublishedByContentWOPublishable() {
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_PUBLISHED
			)
		));

		//処理実行
		$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		$this->assertFalse($result);

		//期待値の生成
		$expected = array(
			'status' => array(__d('net_commons', 'Invalid request.'))
		);

		//テスト実施
		$this->assertEquals($this->FaqQuestion->validationErrors, $expected);
	}

/**
 * Expect to save as content approved by user with content_publishable privilege
 *
 * @return void
 */
	public function testApprovedByContentPublishable() {
		//公開権限セット
		$this->FaqQuestion->Behaviors->attach('Publishable');
		$this->FaqQuestion->Behaviors->Publishable->setup($this->FaqQuestion, ['contentPublishable' => true]);

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_APPROVED
			)
		));

		//処理実行
		$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		$this->assertFalse($result);

		//期待値の生成
		$expected = array(
			'status' => array(__d('net_commons', 'Invalid request.'))
		);

		//テスト実施
		$this->assertEquals($this->FaqQuestion->validationErrors, $expected);
	}

/**
 * Expect to save as content approved by user without content_publishable privilege
 *
 * @return void
 */
	public function testApprovedByContentWOPublishable() {
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_APPROVED
			)
		));

		//処理実行
		$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		$this->assertTrue($result);
	}

/**
 * Expect to save as content disaproved by user with content_publishable privilege
 *
 * @return void
 */
	public function testDisaprovedByContentPublishable() {
		//公開権限セット
		$this->FaqQuestion->Behaviors->attach('Publishable');
		$this->FaqQuestion->Behaviors->Publishable->setup($this->FaqQuestion, ['contentPublishable' => true]);

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'status' => WorkflowComponent::STATUS_DISAPPROVED
			)
		));
		unset($data['FaqQuestion']['id']);

		//処理実行
		$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		$this->assertTrue($result);
	}

/**
 * Expect to save as content disaproved by user without content_publishable privilege
 *
 * @return void
 */
	public function testDisaprovedByContentWOPublishable() {
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_DISAPPROVED
			)
		));

		//処理実行
		$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		$this->assertFalse($result);

		//期待値の生成
		$expected = array(
			'status' => array(__d('net_commons', 'Invalid request.'))
		);

		//テスト実施
		$this->assertEquals($this->FaqQuestion->validationErrors, $expected);
	}

/**
 * Expect to save as content in draft by user with content_publishable privilege
 *
 * @return void
 */
	public function testInDraftByContentPublishable() {
		//公開権限セット
		$this->FaqQuestion->Behaviors->attach('Publishable');
		$this->FaqQuestion->Behaviors->Publishable->setup($this->FaqQuestion, ['contentPublishable' => true]);

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_IN_DRAFT
			)
		));

		//処理実行
		$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		$this->assertTrue($result);
	}

/**
 * Expect to save as content in draft by user without content_publishable privilege
 *
 * @return void
 */
	public function testInDraftByContentWOPublishable() {
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_IN_DRAFT
			)
		));

		//処理実行
		$result = $this->FaqQuestion->validateFaqQuestion($data, ['faqQuestionOrder', 'comment']);
		$this->assertTrue($result);
	}

/**
 * Expect FaqQuestion `faq_id` error by notBlank error on update
 *
 * @return void
 */
	public function testFaqIdErrorByNumberOnUpdate() {
		$field = 'faq_id';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				//'id' => null,
				'status' => WorkflowComponent::STATUS_IN_DRAFT
			)
		));

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施
		foreach ($this->validateNumber as $check) {
			$data['FaqQuestion'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect FaqQuestion `key` error by notBlank error on update
 *
 * @return void
 */
	public function testKeyErrorByNotEmptyOnUpdate() {
		$field = 'key';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				//'id' => null,
				'status' => WorkflowComponent::STATUS_IN_DRAFT
			)
		));

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['FaqQuestion'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['FaqQuestion'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect FaqQuestion `question` error by notBlank error on update
 *
 * @return void
 */
	public function testQuestionErrorByNotEmpty() {
		$field = 'question';
		$message = sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'Question'));

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_IN_DRAFT
			)
		));

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['FaqQuestion'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['FaqQuestion'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect FaqQuestion `answer` error by notBlank error on update
 *
 * @return void
 */
	public function testAnswerErrorByNotEmpty() {
		$field = 'answer';
		$message = sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'Answer'));

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_IN_DRAFT
			)
		));

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施(カラムなし)
		unset($data['FaqQuestion'][$field]);
		$this->__assertValidationError($field, $data, $expected);

		//テスト実施
		foreach ($this->validateNotEmpty as $check) {
			$data['FaqQuestion'][$field] = $check;
			$this->__assertValidationError($field, $data, $expected);
		}
	}

/**
 * Expect FaqQuestionOrder validation error
 *
 * @return void
 */
	public function testFaqQuestionOrderValidationError() {
		$field = 'faq_question_key';
		$message = __d('net_commons', 'Invalid request.');

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				//'id' => null,
				'status' => WorkflowComponent::STATUS_IN_DRAFT
			),
			'FaqQuestionOrder' => array(
				'faq_question_key' => null
			),
		));

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施
		$data['FaqQuestionOrder'][$field] = '';
		$this->__assertValidationError($field, $data, $expected);
	}

/**
 * Expect WorkflowComment validation error
 *
 * @return void
 */
	public function testFaqCommentValidationError() {
		//公開権限セット
		$this->FaqQuestion->Behaviors->attach('Publishable');
		$this->FaqQuestion->Behaviors->Publishable->setup($this->FaqQuestion, ['contentPublishable' => true]);

		//データ生成
		$field = 'comment';
		$message = __d('net_commons', 'If it is not approved, comment is a required input.');

		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				//'id' => null,
				'status' => WorkflowComponent::STATUS_DISAPPROVED
			),
		));

		//期待値
		$expected = array(
			$field => array($message)
		);

		//テスト実施
		$data['WorkflowComment'][$field] = '';
		$this->__assertValidationError($field, $data, $expected);
	}

}
