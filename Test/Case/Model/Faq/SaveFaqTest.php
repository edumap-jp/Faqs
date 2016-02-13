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

App::uses('NetCommonsSaveTest', 'NetCommons.TestSuite');

/**
 * Faq::saveFaq()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\Faq
 */
class FaqSaveFaqTest extends NetCommonsSaveTest {

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
	protected $_methodName = 'saveFaq';

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
				'language_id' => '2',
			),
			'FaqSetting' => array(
				'id' => $faqSettingId,
				'faq_key' => $faqKey,
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
			array($this->__getData(), 'Faqs.Faq', 'save'),
			array($this->__getData(null), 'Faqs.FaqSetting', 'save'),
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
			array($this->__getData(), 'Faqs.Faq'),
		);
	}

}
