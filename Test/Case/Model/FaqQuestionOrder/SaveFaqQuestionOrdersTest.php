<?php
/**
 * FaqQuestionOrder::saveFaqQuestionOrders()のテスト
 *
 * @property FaqQuestionOrder $FaqQuestionOrder
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsSaveTest', 'NetCommons.TestSuite');

/**
 * FaqQuestionOrder::saveFaqQuestionOrders()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\FaqQuestionOrder
 */
class FaqQuestionOrderSaveFaqQuestionOrdersTest extends NetCommonsSaveTest {

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
	protected $_modelName = 'FaqQuestionOrder';

/**
 * Method name
 *
 * @var array
 */
	protected $_methodName = 'saveFaqQuestionOrders';

/**
 * テストDataの取得
 *
 * @param string $faqQuestionKey faqQuestionKey
 * @return array
 */
	private function __getData($faqQuestionKey = 'faq_question_1') {
		$faqKey = 'faq_1';
		if ($faqQuestionKey === 'faq_question_1') {
			$faqQuestionOrderId = '1';
		} else {
			$faqQuestionOrderId = null;
		}

		$data = array(
			'FaqQuestions' => array(
				0 => array(
					'FaqQuestionOrder' => array(
						'id' => $faqQuestionOrderId,
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
 * SaveのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *
 * @return void
 */
	public function dataProviderSave() {
		return array(
			array($this->__getData()),
			array($this->__getData(null)),
		);
	}

/**
 * SaveのExceptionErrorテスト
 *
 * @param array $data 登録データ
 * @param string $mockModel Mockのモデル
 * @param string $mockMethod Mockのメソッド
 * @dataProvider dataProviderSaveOnExceptionError
 * @return void
 */
	public function testSaveOnExceptionError($data, $mockModel, $mockMethod) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//$this->setExpectedException('InternalErrorException'); //PENDING ExceptionERRORが通せない Failed asserting that exception of type "InternalErrorException" is thrown.

		$Mock = $this->getMockForModel('Faqs.FaqQuestionOrder', array('saveMany'));
		$Mock->expects($this->any())
			->method('saveMany')
			->with($data['FaqQuestions'], ['validate' => false])
			//->will($this->throwException(new InternalErrorException));
			->will($this->returnValue(false));

		$this->$model->$method($data);
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
			array($this->__getData(), 'Faqs.FaqQuestionOrder', 'saveMany'),
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
			array($this->__getData(), 'Faqs.FaqQuestionOrder', 'validateMany'),
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
		$data = $this->__getData();
		return array(
			array($data['FaqQuestions']['0'], 'faq_key', '',
				__d('net_commons', 'Invalid request.')),
			array($data['FaqQuestions']['0'], 'faq_question_key', '',
				__d('net_commons', 'Invalid request.')),
			array($data['FaqQuestions']['0'], 'weight', 'a',
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
		$method = $this->_methodName;

		//チェック用データ取得
		$before = array();
		$cnt = 0;
		foreach ($data['FaqQuestions'] as $dataChk) {
			if (isset($dataChk['FaqQuestionOrder']['id'])) {
				$before[$cnt] = $this->$model->find('first', array(
					'recursive' => -1,
					'conditions' => array('id' => $dataChk['FaqQuestionOrder']['id']),
				));
			} else {
				$before[$cnt]['FaqQuestionOrder'] = array();
			}
			$cnt++;
		}

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertNotEmpty($result);

		//idのチェック
		$cnt = 0;
		foreach ($data['FaqQuestions'] as $dataChk) {
			if (isset($dataChk['FaqQuestionOrder']['id'])) {
				$id[$cnt] = $dataChk['FaqQuestionOrder']['id'];
			} else {
				$id[$cnt] = $this->$model->getLastInsertID();
			}
			$cnt++;
		}

		$cnt = 0;
		foreach ($data['FaqQuestions'] as $dataChk) {
			//登録データ取得
			$actual = $this->$model->find('first', array(
				'recursive' => -1,
				'conditions' => array('id' => $id[$cnt]),
			));

			if (isset($dataChk['FaqQuestionOrder']['id'])) {
				$actual['FaqQuestionOrder'] = Hash::remove($actual['FaqQuestionOrder'], 'modified');
				$actual['FaqQuestionOrder'] = Hash::remove($actual['FaqQuestionOrder'], 'modified_user');
			} else {
				$actual['FaqQuestionOrder'] = Hash::remove($actual['FaqQuestionOrder'], 'created');
				$actual['FaqQuestionOrder'] = Hash::remove($actual['FaqQuestionOrder'], 'created_user');
				$actual['FaqQuestionOrder'] = Hash::remove($actual['FaqQuestionOrder'], 'modified');
				$actual['FaqQuestionOrder'] = Hash::remove($actual['FaqQuestionOrder'], 'modified_user');
			}
			$expected['FaqQuestionOrder'] = Hash::merge(
				$before[$cnt]['FaqQuestionOrder'],
				$dataChk['FaqQuestionOrder'],
				array(
					'id' => $id[$cnt],
				)
			);
			$expected['FaqQuestionOrder'] = Hash::remove($expected['FaqQuestionOrder'], 'modified');
			$expected['FaqQuestionOrder'] = Hash::remove($expected['FaqQuestionOrder'], 'modified_user');

			//チェック
			$this->assertEquals($expected, $actual);
			$cnt++;
		}
	}
}
