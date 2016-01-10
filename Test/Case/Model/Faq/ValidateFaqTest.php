<?php
/**
 * Faq::saveFaq()のテスト
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');

/**
 * Faq::saveFaq()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\Faq
 */
class FaqValidateFaqTest extends NetCommonsValidateTest {

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
 * Model name
 *
 * @var array
 */
	protected $_modelName = 'Faq';

/**
 * Method name
 *
 * @var array
 */
	protected $_methodName = '';

/**
 * テストDataの取得
 *
 * @param string $faqKey faqKey
 * @return array
 */
	private function __getData($faqKey = 'faq_1') {
		$frameId = '6';
		$blockId = '2';
		$blockKey = 'block_1';
		$faqId = '2';
		if ($faqKey === 'faq_1') {
			$faqId = '2';
			$faqSettingId = '1';
		} else {
			$faqId = null;
			$faqSettingId = null;
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
				'name' => 'FaqName',
				'block_id' => $blockId,
			),
			'FaqSetting' => array(
				'id' => $faqSettingId,
				'faq_key' => $faqKey,
			),
		);

		return $data;
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
			array($this->__getData(), 'key', '',
				__d('net_commons', 'Invalid request.')),
			array($this->__getData(), 'block_id', '',
				__d('net_commons', 'Invalid request.')),
			array($this->__getData(), 'name', '',
				sprintf(__d('net_commons', 'Please input %s.'), __d('faqs', 'FAQ Name'))),
		);
	}

/**
 * FaqSettingのValidationErrorテスト
 *
 * @return void
 */
	public function testValidateOnValidationError() {
		$model = $this->_modelName;
		$data = $this->__getData();

		$this->_mockForReturnFalse($model, 'Faqs.FaqSetting', 'validates');
		$this->$model->set($data);
		$result = $this->$model->validates();
		$this->assertFalse($result);
	}

}
