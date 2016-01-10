<?php
/**
 * Faq::createFaq createFaq()のテスト
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
 * Faq::createFaq()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\Faq
 */
class FaqCreateFaqTest extends NetCommonsModelTestCase {

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
	protected $_methodName = 'createFaq';

/**
 * createFaqのテスト
 *
 * @param array $keyData 生成するキー情報
 * @dataProvider dataProviderCreate
 * @return void
 */
	public function testCreate($keyData) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//事前準備
		$testCurrentData = Hash::expand($keyData);
		Current::$current = Hash::merge(Current::$current, $testCurrentData);

		//期待値
		$expected = Hash::merge(
			$this->$model->createAll(array(
					'Block' => array('plugin_key' => 'blocks'),
			)),
			$this->$model->FaqSetting->create()
		);

		//テスト実行
		$result = $this->$model->$method();

		//評価
		$this->assertContains(__d('faqs', 'New FAQ %s', ''), $result['Faq']['name']);
		unset($result['Faq']['name']);
		unset($expected['Faq']['name']);
		$this->assertEquals($result, $expected);
	}

/**
 * createFqaのDataProvider
 *
 * #### 戻り値
 *  - array 生成するキー情報
 *
 * @return array
 */
	public function dataProviderCreate() {
		$keyData = array('Block.id' => '1', 'Room.id' => '1', 'Language.id' => '2');

		return array(
			array($keyData),
		);
	}

}
