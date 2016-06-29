<?php
/**
 * いいね・わるいねの追加 migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * いいね・わるいねの追加 migration
 * Issue https://github.com/NetCommons3/Faqs/issues/78
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Config\Migration
 */
class AddLikeAndUnlike extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'Add_like_and_unlike';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'faq_settings' => array(
					'use_like' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'after' => 'use_workflow'),
					'use_unlike' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'after' => 'use_like'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'faq_settings' => array('use_like', 'use_unlike'),
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
