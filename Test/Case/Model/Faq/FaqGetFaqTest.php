<?php
/**
 * Faq::getFaq()のテスト
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqTestBase', 'Faqs.Test/Case/Model/Faq');

/**
 * Faq::getFaq()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqGetFaqTest extends FaqTestBase {

/**
 * 正常テスト
 *
 * @return void
 */
	public function testGetFaq() {
		//事前データセット
		Current::$current['Block']['id'] = '2';
		Current::$current['Room']['id'] = '1';
		Current::$current['Language']['id'] = '2';
		$faqId = '2';
		$faqKey = 'faq_1';

		//期待値
		$expected = $this->Faq->findById($faqId);
		$expected = Hash::merge($expected, $this->FaqSetting->find('first', array(
			'recursive' => -1,
			'conditions' => array('faq_key' => $faqKey),
		)));

		//テスト実施
		$result = $this->Faq->getFaq();

		//評価
		$this->_assertData($expected, $result);
	}

/**
 * ブロックIDなし
 *
 * @return void
 */
	public function testGetFaqWOBlockId() {
		//事前データセット
		Current::$current['Block']['id'] = '99999';
		Current::$current['Room']['id'] = '1';
		Current::$current['Language']['id'] = '2';
		$faqId = '2';
		$faqKey = 'faq_1';

		//期待値
		$expected = false;

		//テスト実施
		$result = $this->Faq->getFaq();

		//評価
		$this->_assertData($expected, $result);
	}
}
