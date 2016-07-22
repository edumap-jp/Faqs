<?php
/**
 * FaqSetting::getFaqSetting()のテスト
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * FaqSetting::getFaqSetting()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model\FaqSetting
 */
class FaqSettingGetFaqSettingTest extends NetCommonsGetTest {

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
		'plugin.faqs.faq',
		'plugin.faqs.block_setting_for_faq',
		'plugin.likes.like',
		'plugin.likes.likes_user',
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
	protected $_methodName = 'getFaqSetting';

/**
 * GetFaqSettingのテスト - データあり
 *
 * @return void
 */
	public function testGet() {
		$model = $this->_modelName;
		$method = $this->_methodName;
		Current::write('Block.key', 'block_1');
		Current::write('Language.id', '2');

		//テスト実行
		$result = $this->$model->$method();

		//debug($result);
		$this->assertCount(1, $result);
	}

/**
 * GetFaqSettingのテスト - データなしの場合、新規登録データあり
 *
 * @return void
 */
	public function testGetEmpty() {
		$model = $this->_modelName;
		$method = $this->_methodName;
		Current::write('Block.key', 'block_xxx');
		Current::write('Language.id', '2');

		//テスト実行
		$result = $this->$model->$method();

		//debug($result);
		$this->assertCount(1, $result);
	}

}
