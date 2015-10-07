<?php
/**
 * FaqQuestion::deleteFaqQuestion()のテスト
 *
 * @property FaqQuestion $Announcement
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowDeleteTest', 'Workflow.TestSuite');

/**
 * FaqQuestion::deleteFaqQuestion()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\Announcement
 */
class FaqQuestionDeleteFaqQuestionTest extends WorkflowDeleteTest {

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
 * data
 *
 * @var array
 */
	public $data = array(
		'Block' => array(
			'id' => '2',
			'key' => 'block_1',
		),
		'FaqQuestion' => array(
			'key' => 'faq_question_1',
		),
	);

/**
 * DeleteのDataProvider
 *
 * ### 戻り値
 *  - data: 削除データ
 *  - model: モデル名
 *  - method: メソッド
 *  - associationModels: 削除確認の関連モデル array(model => conditions)
 *
 * @return void
 */
	public function dataProviderDelete() {
		return array(
			array($this->data, 'FaqQuestion', 'deleteFaqQuestion',
				array('FaqQuestionOrder' => array('faq_question_key' => $this->data['FaqQuestion']['key']))
			),
		);
	}

/**
 * ExceptionErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - model モデル名
 *  - method メソッド
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return void
 */
	public function dataProviderDeleteOnExceptionError() {
		return array(
			array($this->data, 'FaqQuestion', 'deleteFaqQuestion', 'Faqs.FaqQuestion', 'deleteAll'),
			array($this->data, 'FaqQuestion', 'deleteFaqQuestion', 'Faqs.FaqQuestionOrder', 'deleteAll'),
		);
	}

}
