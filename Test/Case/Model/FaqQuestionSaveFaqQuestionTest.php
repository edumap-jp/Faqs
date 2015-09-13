<?php
/**
 * Test of FaqQuestion->saveFaqQuestion()
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
 * Test of FaqQuestion->saveFaqQuestion()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class FaqQuestionSaveFaqQuestionTest extends FaqQuestionTestBase {

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultData = array(
		'Block' => array(
			'id' => '100',
			'key' => 'block_100',
		),
		'Faq' => array(
			'id' => '1',
			'key' => 'faq_1',
		),
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
		'Comment' => array(
			'comment' => 'Add comment',
		)
	);

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultExpected = array(
		'Faq' => array(
			'block_id' => '100',
			'name' => 'faq name 100',
			'is_auto_translated' => false,
			'translation_engine' => null,
			'created_user' => '0',
		),
		'FaqQuestion' => array(
			'created_user' => '1',
		),
		'FaqQuestionOrder' => array(
			'created_user' => null,
		),
		'TrackableCreator' => array(
			'id' => '1',
		)
	);

/**
 * Expect to save as content published by user with content_publishable privilege
 *
 * @return void
 */
	public function testSaveOnUpdate() {
		//公開権限セット
		$this->FaqQuestion->Behaviors->attach('Publishable');
		$this->FaqQuestion->Behaviors->Publishable->setup($this->FaqQuestion, ['contentPublishable' => true]);

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'status' => WorkflowComponent::STATUS_PUBLISHED
			)
		));

		//処理実行
		$result = $this->FaqQuestion->saveFaqQuestion($data);
		$this->assertNotEmpty($result);

		//成否のデータ取得
		$faqId = '1';
		$faqQuestionKey = 'faq_question_1';

		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey);
		$result = Hash::remove($result, 'Category');
		$result = Hash::remove($result, 'CategoryOrder');
		$result = Hash::remove($result, 'TrackableCreator.username');

		//期待値の生成
		$expected = Hash::merge($data, $this->__defaultExpected, array(
			'FaqQuestion' => array(
				'is_active' => true,
				'is_latest' => true,
			),
			'FaqQuestionOrder' => array(
				'weight' => '1',
			),
		));
		$expected = Hash::remove($expected, 'Comment');
		$expected = Hash::remove($expected, 'Block');

		//テスト実施
		$this->_assertArray($expected, $result, 2, ['created', 'modified', 'modified_user']);

		$this->assertEquals(1, $this->Comment->find('count', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $faqQuestionKey),
		)));
	}

/**
 * Expect to edit content on workflow
 *
 * @return void
 */
	public function testEditOnWorkflow() {
		//公開権限セット
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'status' => WorkflowComponent::STATUS_APPROVED
			)
		));

		//処理実行
		$result = $this->FaqQuestion->saveFaqQuestion($data);
		$this->assertNotEmpty($result);

		//成否のデータ取得
		$faqId = '1';
		$faqQuestionKey = 'faq_question_1';
		$faqQuestionId = $this->FaqQuestion->getLastInsertID();

		$conditions = array(
			'FaqQuestion.id' => $faqQuestionId
		);
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, $conditions);
		$result = Hash::remove($result, 'Category');
		$result = Hash::remove($result, 'CategoryOrder');
		$result = Hash::remove($result, 'TrackableCreator.username');

		//期待値の生成
		$expected = Hash::merge($data, $this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'is_active' => false,
				'is_latest' => true,
			),
			'FaqQuestionOrder' => array(
				'weight' => '1',
			),
		));
		$expected = Hash::remove($expected, 'Comment');
		$expected = Hash::remove($expected, 'Block');

		//テスト実施
		$this->_assertArray($expected, $result, 2, ['created', 'modified', 'modified_user']);

		$this->assertEquals(1, $this->Comment->find('count', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $faqQuestionKey),
		)));
	}

/**
 * Expect to edit content on workflow
 *
 * @return void
 */
	public function testAddOnWorkflow() {
		//公開権限セット
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'id' => null,
				'key' => null,
				'status' => WorkflowComponent::STATUS_APPROVED
			),
			'FaqQuestionOrder' => array(
				'id' => null,
				'faq_question_key' => null,
			),
		));

		//処理実行
		$result = $this->FaqQuestion->saveFaqQuestion($data);
		$this->assertNotEmpty($result);

		//成否のデータ取得
		$faqId = '1';
		$faqQuestionKey = $result['FaqQuestion']['key'];
		$faqQuestionId = $this->FaqQuestion->getLastInsertID();
		$faqQuestionOrderId = $this->FaqQuestionOrder->getLastInsertID();

		$conditions = array(
			'FaqQuestion.id' => $faqQuestionId
		);
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, $conditions);
		$result = Hash::remove($result, 'Category');
		$result = Hash::remove($result, 'CategoryOrder');
		$result = Hash::remove($result, 'TrackableCreator.username');

		//期待値の生成
		$expected = Hash::merge($data, $this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'key' => $faqQuestionKey,
				'is_active' => false,
				'is_latest' => true,
			),
			'FaqQuestionOrder' => array(
				'id' => $faqQuestionOrderId,
				'faq_question_key' => $faqQuestionKey,
				'weight' => '4',
			),
		));
		$expected = Hash::remove($expected, 'Comment');
		$expected = Hash::remove($expected, 'Block');

		//テスト実施
		$this->_assertArray($expected, $result, 2, ['created', 'modified', 'modified_user']);

		$this->assertEquals(1, $this->Comment->find('count', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $faqQuestionKey),
		)));
	}

/**
 * Expect to save as content published by user with content_publishable privilege
 *
 * @return void
 */
	public function testPublishedByContentPublishable() {
		//公開権限セット
		$this->FaqQuestion->Behaviors->attach('Publishable');
		$this->FaqQuestion->Behaviors->Publishable->setup($this->FaqQuestion, ['contentPublishable' => true]);

		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'status' => WorkflowComponent::STATUS_PUBLISHED
			)
		));
		unset($data['FaqQuestion']['id']);

		//処理実行
		$result = $this->FaqQuestion->saveFaqQuestion($data);
		$this->assertNotEmpty($result);
		$faqQuestionId = $this->FaqQuestion->getLastInsertID();

		//成否のデータ取得
		$faqId = '1';
		$faqQuestionKey = 'faq_question_1';

		$conditions = array(
			'FaqQuestion.id' => $faqQuestionId
		);
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, $conditions);
		$result = Hash::remove($result, 'Category');
		$result = Hash::remove($result, 'CategoryOrder');
		$result = Hash::remove($result, 'TrackableCreator.username');

		//期待値の生成
		$expected = Hash::merge($data, $this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'is_active' => true,
				'is_latest' => true,
			),
			'FaqQuestionOrder' => array(
				'weight' => '1',
			),
		));
		$expected = Hash::remove($expected, 'Comment');
		$expected = Hash::remove($expected, 'Block');

		//テスト実施
		$this->_assertArray($expected, $result, 2, ['created', 'modified', 'modified_user']);

		$this->assertEquals(1, $this->Comment->find('count', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $faqQuestionKey),
		)));
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
		$result = $this->FaqQuestion->saveFaqQuestion($data);
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
		$result = $this->FaqQuestion->saveFaqQuestion($data);
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
		$result = $this->FaqQuestion->saveFaqQuestion($data);
		$this->assertNotEmpty($result);

		//成否のデータ取得
		$faqId = '1';
		$faqQuestionKey = 'faq_question_1';
		$faqQuestionId = $this->FaqQuestion->getLastInsertID();
		$conditions = array(
			'FaqQuestion.id' => $faqQuestionId
		);
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, $conditions);
		$result = Hash::remove($result, 'Category');
		$result = Hash::remove($result, 'CategoryOrder');
		$result = Hash::remove($result, 'TrackableCreator.username');

		//期待値の生成
		$expected = Hash::merge($data, $this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'is_active' => false,
				'is_latest' => true,
			),
			'FaqQuestionOrder' => array(
				'weight' => '1',
			),
		));
		$expected = Hash::remove($expected, 'Comment');
		$expected = Hash::remove($expected, 'Block');

		//テスト実施
		$this->_assertArray($expected, $result, 2, ['created', 'modified', 'modified_user']);

		$this->assertEquals(1, $this->Comment->find('count', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $faqQuestionKey),
		)));
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
		$result = $this->FaqQuestion->saveFaqQuestion($data);
		$this->assertNotEmpty($result);
		$faqQuestionId = $this->FaqQuestion->getLastInsertID();

		//成否のデータ取得
		$faqId = '1';
		$faqQuestionKey = 'faq_question_1';

		$conditions = array(
			'FaqQuestion.id' => $faqQuestionId
		);
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, $conditions);
		$result = Hash::remove($result, 'Category');
		$result = Hash::remove($result, 'CategoryOrder');
		$result = Hash::remove($result, 'TrackableCreator.username');

		//期待値の生成
		$expected = Hash::merge($data, $this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'is_active' => false,
				'is_latest' => true,
			),
			'FaqQuestionOrder' => array(
				'weight' => '1',
			),
		));
		$expected = Hash::remove($expected, 'Comment');
		$expected = Hash::remove($expected, 'Block');

		//テスト実施
		$this->_assertArray($expected, $result, 2, ['created', 'modified', 'modified_user']);

		$this->assertEquals(1, $this->Comment->find('count', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $faqQuestionKey),
		)));
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
		$result = $this->FaqQuestion->saveFaqQuestion($data);
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
		$result = $this->FaqQuestion->saveFaqQuestion($data);
		$this->assertNotEmpty($result);

		//成否のデータ取得
		$faqId = '1';
		$faqQuestionKey = 'faq_question_1';
		$faqQuestionId = $this->FaqQuestion->getLastInsertID();
		$conditions = array(
			'FaqQuestion.id' => $faqQuestionId
		);
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, $conditions);
		$result = Hash::remove($result, 'Category');
		$result = Hash::remove($result, 'CategoryOrder');
		$result = Hash::remove($result, 'TrackableCreator.username');

		//期待値の生成
		$expected = Hash::merge($data, $this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'is_active' => false,
				'is_latest' => true,
			),
			'FaqQuestionOrder' => array(
				'weight' => '1',
			),
		));
		$expected = Hash::remove($expected, 'Comment');
		$expected = Hash::remove($expected, 'Block');

		//テスト実施
		$this->_assertArray($expected, $result, 2, ['created', 'modified', 'modified_user']);

		$this->assertEquals(1, $this->Comment->find('count', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $faqQuestionKey),
		)));
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
		$result = $this->FaqQuestion->saveFaqQuestion($data);
		$this->assertNotEmpty($result);

		//成否のデータ取得
		$faqId = '1';
		$faqQuestionKey = 'faq_question_1';
		$faqQuestionId = $this->FaqQuestion->getLastInsertID();
		$conditions = array(
			'FaqQuestion.id' => $faqQuestionId
		);
		$result = $this->FaqQuestion->getFaqQuestion($faqId, $faqQuestionKey, $conditions);
		$result = Hash::remove($result, 'Category');
		$result = Hash::remove($result, 'CategoryOrder');
		$result = Hash::remove($result, 'TrackableCreator.username');

		//期待値の生成
		$expected = Hash::merge($data, $this->__defaultExpected, array(
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'is_active' => false,
				'is_latest' => true,
			),
			'FaqQuestionOrder' => array(
				'weight' => '1',
			),
		));
		$expected = Hash::remove($expected, 'Comment');
		$expected = Hash::remove($expected, 'Block');

		//テスト実施
		$this->_assertArray($expected, $result, 2, ['created', 'modified', 'modified_user']);

		$this->assertEquals(1, $this->Comment->find('count', array(
			'recursive' => -1,
			'conditions' => array('content_key' => $faqQuestionKey),
		)));
	}

/**
 * Expect to fail on FaqQuestion->save()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnSave() {
		$this->setExpectedException('InternalErrorException');

		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'status' => WorkflowComponent::STATUS_APPROVED
			)
		));

		$this->FaqQuestion = $this->getMockForModel('Faqs.FaqQuestion', array('save'));
		$this->FaqQuestion->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->FaqQuestion->saveFaqQuestion($data);
	}

/**
 * Expect to fail on FaqQuestionOrder->save()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnFaqQuestionOrderSave() {
		$this->setExpectedException('InternalErrorException');

		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'status' => WorkflowComponent::STATUS_APPROVED
			)
		));

		$this->FaqQuestionOrder = $this->getMockForModel('Faqs.FaqQuestionOrder', array('save'));
		$this->FaqQuestionOrder->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->FaqQuestion->saveFaqQuestion($data);
	}

/**
 * Expect to fail on Comment->save()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnCommentSave() {
		$this->setExpectedException('InternalErrorException');

		$data = Hash::merge($this->__defaultData, array(
			'FaqQuestion' => array(
				'status' => WorkflowComponent::STATUS_APPROVED
			)
		));

		$this->Comment = $this->getMockForModel('Comments.Comment', array('save'));
		$this->Comment->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->FaqQuestion->saveFaqQuestion($data);
	}

}
