<?php
/**
 * Faq::deleteFaq()のテスト
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Faqs', 'Faqs.Model');
App::uses('FaqFrameSetting', 'Faqs.Model');
App::uses('NetCommonsDeleteTest', 'NetCommons.TestSuite');

/**
 * Faq::deleteFaq()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\Faq
 */
class FaqDeleteFaqTest extends NetCommonsDeleteTest {

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
	protected $_modelName = 'Faq';

/**
 * Method name
 *
 * @var array
 */
	protected $_methodName = 'deleteFaq';

/**
 * data
 *
 * @var array
 */
	private $__data = array(
			'Block' => array(
				'id' => '2',
				'key' => 'block_1',
			),
			'Faq' => array(
				'id' => '2',
				'block_key' => 'block_1',
				'key' => 'faq_1',
			),
	);

/**
 * DeleteのDataProvider
 *
 * #### 戻り値
 *  - data: 削除データ
 *  - associationModels: 削除確認の関連モデル array(model => conditions)
 *
 * @return array
 */
	public function dataProviderDelete() {
		$association = array(
			'FaqSetting' => array(
				'faq_key' => 'faq_1',
			),
			'FaqQuestion' => array(
				'faq_id' => '2',
			),
			'FaqQuestionOrder' => array(
				'faq_key' => 'faq_1',
			),
		);

		return array(
			array($this->__data, $association )
		);
	}

/**
 * ExceptionErrorのDataProvider
 *
 * #### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return void
 */
	public function dataProviderDeleteOnExceptionError() {
		return array(
			array($this->__data, 'Faqs.Faq', 'deleteAll'),
			array($this->__data, 'Faqs.FaqSetting', 'deleteAll'),
			array($this->__data, 'Faqs.FaqQuestion', 'deleteAll'),
			array($this->__data, 'Faqs.FaqQuestionOrder', 'deleteAll'),
		);
	}

}
