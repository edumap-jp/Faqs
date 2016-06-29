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
		'plugin.faqs.faq_setting',
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
 * GetFaqSettingのテスト
 *
 * @param string $getkey 取得するキー情報($faqkey)
 * @param array $expected 期待値（取得する情報）
 * @dataProvider dataProviderGet
 *
 * @return void
 */
	public function testGet($getkey, $expected) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//テスト実行
		$result = $this->$model->$method($getkey);

		foreach ($expected as $key => $val) {
			$this->assertEquals($result[$model][$key], $val);
		}
	}

/**
 * getFaqSettingのDataProvider
 *
 * #### 戻り値
 *  - array 取得するキー情報
 *  - array 期待値 （取得する情報）
 *
 * @return array
 */
	public function dataProviderGet() {
		$existData = 'faq_1'; // データあり
		$notExistData = 'faq_xx'; // データなし

		return array(
			array($existData, array('id' => '1')), // 存在する
			array($notExistData, array()), // 存在しない
		);
	}

}
