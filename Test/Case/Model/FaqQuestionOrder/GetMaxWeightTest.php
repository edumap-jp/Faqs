<?php
/**
 * FaqQuestionOrder::getMaxWeight()のテスト
 *
 * @property FaqQuestionOrder $FaqQuestionOrder
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * FaqQuestionOrder::getMaxWeight()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\FaqQuestionOrder
 */
class FaqQuestionOrderGetMaxWeightTest extends NetCommonsGetTest {

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
		'plugin.categories.categories_language',
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
	protected $_modelName = 'FaqQuestionOrder';

/**
 * Method name
 *
 * @var array
 */
	protected $_methodName = 'getMaxWeight';

/**
 * getMaxWeightのテスト
 *
 * @param string $keyData faqs.key
 * @param array $expected 期待値
 * @dataProvider dataProviderGetMaxWeight
 *
 * @return void
 */
	public function testGetMaxWeight($keyData, $expected) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//テスト実行
		$result = $this->$model->$method($keyData);

		//チェック
		$this->assertEquals($expected, $result);
	}

/**
 * getMaxWeightのDataProvider
 *
 * #### 戻り値
 *  - string  faqs.key
 *  - array 期待値 （取得したキー情報）
 *
 * @return array
 */
	public function dataProviderGetMaxWeight() {
		return array(
			array('faq_1', 3), // 存在する
			array('faq_2', 0), // 存在しない
		);
	}

}
