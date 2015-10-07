<?php
/**
 * Common code of Faq model test
 *
 * @property Faq $Faq
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsModelTestBase', 'Faqs.Test/Case/Model');

/**
 * Common code of Faq model test
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqTestBase extends FaqsModelTestBase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Faq = ClassRegistry::init('Faqs.Faq');
		$this->FaqSetting = ClassRegistry::init('Faqs.FaqSetting');
		$this->FaqQuestion = ClassRegistry::init('Faqs.FaqQuestion');
		$this->FaqQuestionOrder = ClassRegistry::init('Faqs.FaqQuestionOrder');
		$this->Category = ClassRegistry::init('Categories.Category');
		$this->CategoryOrder = ClassRegistry::init('Categories.CategoryOrder');
		$this->Block = ClassRegistry::init('Blocks.Block');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Faq);
		unset($this->FaqSetting);
		unset($this->FaqQuestion);
		unset($this->FaqQuestionOrder);
		unset($this->Category);
		unset($this->CategoryOrder);
		unset($this->Block);
		parent::tearDown();
	}
}
