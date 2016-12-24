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
class AddFieldsForM17n1 extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_fields_for_m17n_1';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
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
		$FaqQuestion = $this->generateModel('FaqQuestion');

		$fqsTable = $FaqQuestion->tablePrefix . 'faqs Faq';
		$faqQuestionTable = $FaqQuestion->tablePrefix . 'faq_questions FaqQuestion';

		if ($direction === 'up') {
			$sql = 'UPDATE ' . $fqsTable . ', ' . $faqQuestionTable .
					' SET FaqQuestion.faq_key = Faq.key' .
					' WHERE FaqQuestion.faq_id' . ' = Faq.id' .
					'';
		} else {
			$sql = 'UPDATE ' . $fqsTable . ', ' . $faqQuestionTable .
					' SET FaqQuestion.faq_id = Faq.id' .
					' WHERE FaqQuestion.faq_key' . ' = Faq.key' .
					' AND FaqQuestion.language_id' . ' = Faq.language_id' .
					'';
		}
		$FaqQuestion->query($sql);
		return true;
	}
}
