<?php
/**
 * Common code of FaqSetting model test
 *
 * @property FaqSetting $FaqSetting
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsModelTestBase', 'Faqs.Test/Case/Model');

/**
 * Common code of FaqSetting model test
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqSettingTestBase extends FaqsModelTestBase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Faq = ClassRegistry::init('Faqs.Faq');
		$this->FaqSetting = ClassRegistry::init('Faqs.FaqSetting');
		$this->BlockRolePermission = ClassRegistry::init('Blocks.BlockRolePermission');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Faq);
		unset($this->FaqSetting);
		unset($this->BlockRolePermission);
		parent::tearDown();
	}
}
