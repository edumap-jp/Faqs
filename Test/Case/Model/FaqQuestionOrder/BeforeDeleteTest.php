<?php
/**
 * FaqQuestionOrder::beforeDelete()のテスト
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * FaqQuestionOrder::beforeDelete()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\FaqQuestionOrder
 */
class FaqQuestionOrderBeforeDeleteTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'beforeDelete';

/**
 * beforeDeleteのテスト
 *
 * @param array $data テスト対象の情報
 * @param array $expect チェック対象の情報
 * @dataProvider dataProviderBeforeDelete
 *
 * @return void
 */
	public function testBeforeDelete($data, $expect) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//事前準備
		$this->FaqQuestionOrder->data['FaqQuestionOrder'] = array(
			'faq_key' => $data['faq_key'],
			'faq_question_key' => $data['faq_question_key'],
			'weight' => $data['weight'],
		);

		//テスト実行
		$result = $this->$model->$method();

		//チェック
		$this->assertTrue($result);
		$result = $this->FaqQuestionOrder->find('first', array(
			'recursive' => -1,
			'conditions' => array('faq_question_key' => $expect['faq_question_key']),
		));

		$this->assertEquals($expect['weight'], $result['FaqQuestionOrder']['weight']);
	}

/**
 * beforeDeleteのDataProvider
 *
 * #### 戻り値
 *  - array データ （テスト対象の情報）
 *  - array データ （チェック対象の情報）
 *
 * @return array
 */
	public function dataProviderBeforeDelete() {
		//テストデータ生成
		$data1 = array('faq_key' => 'faq_1', 'faq_question_key' => 'faq_question_2', 'weight' => '2');
		$expect1 = array('faq_question_key' => 'faq_question_3', 'weight' => '2');

		return array(
			array($data1, $expect1),
		);
	}

}
