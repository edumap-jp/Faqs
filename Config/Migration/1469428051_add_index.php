<?php
/**
 * AddIndex migration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * AddIndex migration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Faqs\Config\Migration
 */
class AddIndex extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_index';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'faq_frame_settings' => array(
					'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'フレームKey', 'charset' => 'utf8'),
				),
				'faq_question_orders' => array(
					'faq_question_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'FAQ質問キー', 'charset' => 'utf8'),
				),
				'faq_questions' => array(
					'faq_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
				),
				'faqs' => array(
					'block_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
				),
			),
			'create_field' => array(
				'faq_frame_settings' => array(
					'indexes' => array(
						'frame_key' => array('column' => 'frame_key', 'unique' => 0, 'length' => array('191')),
					),
				),
				'faq_question_orders' => array(
					'indexes' => array(
						'faq_question_key' => array('column' => 'faq_question_key', 'unique' => 0),
					),
				),
				'faq_questions' => array(
					'indexes' => array(
						'faq_id' => array('column' => array('faq_id', 'language_id'), 'unique' => 0),
					),
				),
				'faqs' => array(
					'indexes' => array(
						'block_id' => array('column' => 'block_id', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'faq_frame_settings' => array(
					'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'フレームKey', 'charset' => 'utf8'),
				),
				'faq_question_orders' => array(
					'faq_question_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'FAQ質問キー', 'charset' => 'utf8'),
				),
				'faq_questions' => array(
					'faq_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
				),
				'faqs' => array(
					'block_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
				),
			),
			'drop_field' => array(
				'faq_frame_settings' => array('indexes' => array('frame_key')),
				'faq_question_orders' => array('indexes' => array('faq_question_key')),
				'faq_questions' => array('indexes' => array('faq_id')),
				'faqs' => array('indexes' => array('block_id')),
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
