<?php
/**
 * FaqQuestion::saveFaqQuestion()のテスト
 *
 * @property FaqQuestion $FaqQuestion
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowSaveTest', 'Workflow.TestSuite');

/**
 * FaqQuestion::saveFaqQuestion()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\FaqQuestion
 */
class FaqQuestionSaveFaqQuestionTest extends WorkflowSaveTest {

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
		'plugin.workflow.workflow_comment',
		'plugin.faqs.faq',
		'plugin.faqs.faq_setting',
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
	protected $_methodName = 'saveFaqQuestion';

/**
 * テストDataの取得
 *
 * @param string $faqQuestionKey faqQuestionKey
 * @return array
 */
	private function __getData($faqQuestionKey = 'faq_question_1') {
		$frameId = '6';
		$blockId = '2';
		$blockKey = 'block_1';
		$faqId = '2';
		$faqKey = 'faq_1';
		if ($faqQuestionKey === 'faq_question_1') {
			$faqQuestionId = '2';
			$faqQuestionOrderId = '1';
		} else {
			$faqQuestionId = null;
			$faqQuestionOrderId = null;
		}

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
				'language_id' => '2',
			),
			'FaqQuestion' => array(
				'id' => $faqQuestionId,
				'key' => $faqQuestionKey,
				'faq_id' => $faqId,
				'language_id' => '2',
				'category_id' => '2',
				'status' => WorkflowComponent::STATUS_PUBLISHED,
				'question' => 'Save question',
				'answer' => 'Save answer',
			),
			'FaqQuestionOrder' => array(
				'id' => $faqQuestionOrderId,
				'faq_key' => $faqKey,
				'faq_question_key' => $faqQuestionKey,
			),
			'WorkflowComment' => array(
				'comment' => 'WorkflowComment save test'
			),
		);

		return $data;
	}

/**
 * SaveのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *
 * @return void
 */
	public function dataProviderSave() {
		return array(
			array($this->__getData()), //修正
			array($this->__getData(null)), //新規
		);
	}

/**
 * SaveのExceptionErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return void
 */
	public function dataProviderSaveOnExceptionError() {
		return array(
			array($this->__getData(), 'Faqs.FaqQuestion', 'save'),
			array($this->__getData(null), 'Faqs.FaqQuestionOrder', 'save'),
		);
	}

/**
 * SaveのValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *
 * @return void
 */
	public function dataProviderSaveOnValidationError() {
		return array(
			array($this->__getData(), 'Faqs.FaqQuestion'),
			array($this->__getData(), 'Faqs.FaqQuestionOrder'),
		);
	}

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ
 *
 * @return void
 */
	public function dataProviderValidationError() {
		return array(
			array($this->__getData(), 'question', '',
				sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'Question'))),
			array($this->__getData(), 'answer', '',
				sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'Answer'))),
			array($this->__getData(), 'key', '',
				__d('net_commons', 'Invalid request.')),
			array($this->__getData(), 'faq_id', 'aaa',
				__d('net_commons', 'Invalid request.')),
		);
	}

/**
 * Saveのテスト
 *
 * @param array $data 登録データ
 * @dataProvider dataProviderSave
 * @return void
 */
	public function testSave($data) {
		$model = $this->_modelName;

		//FaqQuestionOrderのテスト前のデータ取得
		if (isset($data['FaqQuestionOrder']['id'])) {
			$before = $this->$model->FaqQuestionOrder->find('first', array(
				'recursive' => -1,
				'conditions' => array('faq_question_key' => $data[$this->$model->alias]['key']),
			));
			$before['FaqQuestionOrder'] = Hash::remove($before['FaqQuestionOrder'], 'modified');
			$before['FaqQuestionOrder'] = Hash::remove($before['FaqQuestionOrder'], 'modified_user');
		} else {
			$max = $this->$model->FaqQuestionOrder->find('first', array(
				'recursive' => -1,
				'fields' => 'id',
				'order' => array('id' => 'desc')
			));
			$maxId = $max['FaqQuestionOrder']['id'] + 1;

			$max = $this->$model->FaqQuestionOrder->find('first', array(
				'recursive' => -1,
				'fields' => 'weight',
				'conditions' => array('faq_key' => $data['FaqQuestionOrder']['faq_key']),
				'order' => array('weight' => 'desc')
			));
			$maxWeight = $max['FaqQuestionOrder']['weight'] + 1;

			$before['FaqQuestionOrder'] = $data['FaqQuestionOrder'];
			$before['FaqQuestionOrder']['id'] = (string)$maxId;
			$before['FaqQuestionOrder']['weight'] = (string)$maxWeight;
		}

		//テスト実施
		$latest = parent::testSave($data);

		//登録処理後のFaqQuestionOrderのチェック
		if (isset($data['FaqQuestionOrder']['id'])) {
			$after = $this->$model->FaqQuestionOrder->find('first', array(
				'recursive' => -1,
				'conditions' => array('faq_question_key' => $data[$this->$model->alias]['key']),
			));

		} else {
			$before['FaqQuestionOrder']['faq_question_key'] = $latest[$this->$model->alias]['key'];

			$after = $this->$model->FaqQuestionOrder->find('first', array(
				'recursive' => -1,
				'order' => array('id' => 'desc')
			));
			$after['FaqQuestionOrder'] = Hash::remove($after['FaqQuestionOrder'], 'created');
			$after['FaqQuestionOrder'] = Hash::remove($after['FaqQuestionOrder'], 'created_user');
		}

		$after['FaqQuestionOrder'] = Hash::remove($after['FaqQuestionOrder'], 'modified');
		$after['FaqQuestionOrder'] = Hash::remove($after['FaqQuestionOrder'], 'modified_user');

		$this->assertEquals($after['FaqQuestionOrder'], $before['FaqQuestionOrder']);
	}

}
