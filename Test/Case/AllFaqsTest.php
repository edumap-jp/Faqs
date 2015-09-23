<?php
/**
 * Faqs All Test Suite
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * Faqs All Test Suite
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case
 * @codeCoverageIgnore
 */
class AllFaqsTest extends CakeTestSuite {

/**
 * All test suite
 *
 * @return CakeTestSuite
 */
	public static function suite() {
		$plugin = preg_replace('/^All([\w]+)Test$/', '$1', __CLASS__);
		$suite = new CakeTestSuite(sprintf('All %s Plugin tests', $plugin));

		NetCommonsCakeTestCase::$plugin = Inflector::underscore($plugin);

		$directory = CakePlugin::path($plugin) . 'Test' . DS . 'Case';
		$Folder = new Folder($directory);
		$exceptions = array(
			'FaqsControllerTestBase.php',
			'FaqsModelTestBase.php',
			'FaqQuestionOrderTestBase.php',
			'FaqQuestionTestBase.php',
			'FaqSettingTestBase.php',
			'FaqTestBase.php',
			//後で削除する
			'BlockRolePermissionsControllerEditTest.php',
			'BlocksControllerAddTest.php',
			'BlocksControllerEditTest.php',
			'BlocksControllerIndexTest.php',
			'FaqQuestionOrdersControllerEditTest.php',
			'FaqQuestionsControllerAddTest.php',
			'FaqQuestionsControllerDeleteTest.php',
			'FaqQuestionsControllerEditTest.php',
			'FaqQuestionsControllerIndexTest.php',
			'FaqQuestionsControllerViewTest.php',
			'FaqsControlleIndexrTest.php',
			'FaqsControllerTestBase.php',
			//後で削除する
			'FaqDeleteFaqTest.php',
			'FaqGetFaqTest.php',
			'FaqQuestionDeleteFaqQuestionTest.php',
			'FaqQuestionGetFaqQuestionsTest.php',
			'FaqQuestionGetFaqQuestionTest.php',
			'FaqQuestionOrderBeforeDeleteTest.php',
			'FaqQuestionOrderGetMaxWeightTest.php',
			'FaqQuestionOrderSaveFaqQuestionOrdersTest.php',
			'FaqQuestionOrderTestBase.php',
			'FaqQuestionOrderValidateFaqQuestionOrderTest.php',
			'FaqQuestionSaveFaqQuestionTest.php',
			'FaqQuestionTestBase.php',
			'FaqQuestionValidateFaqQuestionTest.php',
			'FaqSaveFaqTest.php',
			'FaqSettingGetFaqSettingTest.php',
			'FaqSettingSaveFaqSettingTest.php',
			'FaqSettingTestBase.php',
			'FaqSettingValidateFaqSettingTest.php',
			'FaqsModelTestBase.php',
			'FaqTestBase.php',
			'FaqValidateFaqTest.php',
		);
		$files = $Folder->tree(null, $exceptions, 'files');
		foreach ($files as $file) {
			if (substr($file, -4) === '.php') {
				$suite->addTestFile($file);
			}
		}

		return $suite;
	}
}
