<?php
/**
 * FaqSetting::saveFaqSetting()のテスト
 *
 * @property FaqSetting $FaqSetting
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsSaveTest', 'NetCommons.TestSuite');

/**
 * Faq::saveFaqSetting()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\FaqSetting
 */
class FaqSettingSaveFaqSettingTest extends NetCommonsSaveTest {

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
	protected $_modelName = 'FaqSetting';

/**
 * Method name
 *
 * @var array
 */
	protected $_methodName = 'saveFaqSetting';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		Current::write('Plugin.key', $this->plugin);
		Current::write('Block.key', 'block_1');
	}

/**
 * テストDataの取得
 *
 * @return array
 */
	private function __getData() {
		$data = array(
			'FaqSetting' => array(
				'use_workflow' => '0',
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
 * @return array
 */
	public function dataProviderSave() {
		return array(
			array($this->__getData()),
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

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertNotEmpty($result);

		//登録データ取得
		$actual = $this->$model->getFaqSetting();
		$expected = $data;

		$this->assertEquals($expected, $actual);
	}

/**
 * SaveのExceptionErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array
 */
	public function dataProviderSaveOnExceptionError() {
		$data = $this->__getData();
		return array(
			array($data[$this->_modelName], 'Blocks.BlockSetting', 'saveMany'),
		);
	}

/**
 * SaveのValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *
 * @return array
 */
	public function dataProviderSaveOnValidationError() {
		return array(
			array($this->__getData(), 'Faqs.FaqSetting'),
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
 * @return array
 */
	public function dataProviderValidationError() {
		return array(
			array($this->__getData(), 'use_workflow', 'a',
				__d('net_commons', 'Invalid request.')),
			array($this->__getData(), 'use_like', 'a',
				__d('net_commons', 'Invalid request.')),
			array($this->__getData(), 'use_unlike', 'a',
				__d('net_commons', 'Invalid request.')),
		);
	}

}
