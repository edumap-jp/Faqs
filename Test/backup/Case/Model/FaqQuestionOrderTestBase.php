<?php
/**
 * Common code of FaqQuestionOrder model test
 *
 * @property FaqQuestionOrder $FaqQuestionOrder
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsModelTestBase', 'Faqs.Test/Case/Model');

/**
 * Common code of FaqQuestionOrder model test
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqQuestionOrderTestBase extends FaqsModelTestBase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FaqQuestionOrder = ClassRegistry::init('Faqs.FaqQuestionOrder');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FaqQuestionOrder);
		parent::tearDown();
	}
}
