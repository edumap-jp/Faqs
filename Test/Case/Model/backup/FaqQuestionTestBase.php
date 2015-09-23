<?php
/**
 * Common code of FaqQuestion model test
 *
 * @property FaqQuestion $FaqQuestion
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsModelTestBase', 'Faqs.Test/Case/Model');

/**
 * Common code of FaqQuestion model test
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqQuestionTestBase extends FaqsModelTestBase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FaqQuestion = ClassRegistry::init('Faqs.FaqQuestion');
		$this->FaqQuestionOrder = ClassRegistry::init('Faqs.FaqQuestionOrder');
		$this->Comment = ClassRegistry::init('Comments.Comment');
		$this->Block = ClassRegistry::init('Blocks.Block');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FaqQuestion);
		unset($this->FaqQuestionOrder);
		unset($this->Comment);
		unset($this->Block);
		parent::tearDown();
	}
}
