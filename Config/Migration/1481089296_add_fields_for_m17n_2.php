<?php
/**
 * 多言語化対応
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * 多言語化対応
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Config\Migration
 */
class AddFieldsForM17n2 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_fields_for_m17n_2';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'faq_questions' => array('faq_id', 'indexes' => array('faq_id')),
			),
			'alter_field' => array(
				'faq_questions' => array(
					'faq_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'FAQキー', 'charset' => 'utf8'),
				),
			),
			'create_field' => array(
				'faq_questions' => array(
					'indexes' => array(
						'faq_key' => array('column' => array('faq_key', 'language_id'), 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'faq_questions' => array(
					'faq_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'indexes' => array(
						'faq_id' => array('column' => array('faq_id', 'language_id'), 'unique' => 0),
					),
				),
			),
			'alter_field' => array(
				'faq_questions' => array(
					'faq_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'FAQキー', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'faq_questions' => array('indexes' => array('faq_key')),
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
