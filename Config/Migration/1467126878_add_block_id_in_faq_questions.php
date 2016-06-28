<?php
/**
 * faq_questionsテーブルにblock_idを追加 migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * faq_questionsテーブルにblock_idを追加 migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Config\Migration
 */
class AddBlockIdInFaqQuestions extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Add_block_id_in_faq_questions';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'faq_questions' => array(
					'block_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'faq_id'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'faq_questions' => array('block_id'),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
