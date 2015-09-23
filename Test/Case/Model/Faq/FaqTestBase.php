<?php
/**
 * Common code for test of Faqs
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
 * Common code for test of Faqs
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqTestBase extends FaqsModelTestBase {

/**
 * data
 *
 * @var array
 */
	public $data = array(
		'Frame' => array(
			'id' => '6',
		),
		'Block' => array(
			'id' => '2',
			'language_id' => '2',
			'room_id' => '1',
			'plugin_key' => 'faqs',
			'key' => 'block_1',
			'public_type' => '1',
			'from' => null,
			'to' => null,
		),
		'Faq' => array(
			'id' => '2',
			'block_id' => '2',
			'key' => 'faq_1',
			'name' => 'Faq name 1',
		),
		'FaqSetting' => array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'use_workflow' => '1',
		),
	);

}
