<?php
/**
 * Faq::validates()のテスト
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
 * Faq::validates()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class ValidateErrorTest extends FaqTestBase {

/**
 * Faq.nameのnotBlankエラー
 *
 * @return void
 */
	public function testNameByNotBlank() {
		$this->_assertValidation('notBlank', 'Faq', 'Faq.name', true);
	}
}
