<?php
/**
 * FaqsApp Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * FaqsApp Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Controller
 */
class FaqsAppController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsFrame',
		'Pages.PageLayout',
		'Security',
	);

/**
 * initFaq
 *
 * @param array $contains Optional result sets
 * @return bool True on success, False on failure
 */
	public function initFaq($contains = []) {
		if (! $faq = $this->Faq->getFaq()) {
			$this->throwBadRequest();
			return false;
		}
		$faq = $this->camelizeKeyRecursive($faq);
		$this->set($faq);

		if (in_array('faqSetting', $contains, true)) {
			if (! $faqSetting = $this->FaqSetting->getFaqSetting($faq['faq']['key'])) {
				$faqSetting = $this->FaqSetting->create(
					array('id' => null)
				);
			}
			$faqSetting = $this->camelizeKeyRecursive($faqSetting);
			$this->set($faqSetting);
		}

//		$this->set('userId', (int)$this->Auth->user('id'));

		return true;
	}

/**
 * initTabs
 *
 * @param string $mainActiveTab Main active tab
 * @param string $blockActiveTab Block active tab
 * @return void
 */
	public function initTabs($mainActiveTab, $blockActiveTab) {
		if (isset($this->params['pass'][1])) {
			$blockId = (int)$this->params['pass'][1];
		} else {
			$blockId = null;
		}

		//タブの設定
		$settingTabs = array(
			'tabs' => array(
				'block_index' => array(
					'url' => array(
						'plugin' => $this->params['plugin'],
						'controller' => 'faq_blocks',
						'action' => 'index',
						Current::read('Frame.id'),
					)
				),
			),
			'active' => $mainActiveTab
		);
		$this->set('settingTabs', $settingTabs);

		$blockSettingTabs = array(
			'tabs' => array(
				'block_settings' => array(
					'url' => array(
						'plugin' => $this->params['plugin'],
						'controller' => 'faq_blocks',
						'action' => $this->params['action'],
						Current::read('Frame.id'),
						$blockId
					)
				),
				'role_permissions' => array(
					'url' => array(
						'plugin' => $this->params['plugin'],
						'controller' => 'faq_block_role_permissions',
						'action' => 'edit',
						Current::read('Frame.id'),
						$blockId
					)
				),
			),
			'active' => $blockActiveTab
		);
		$this->set('blockSettingTabs', $blockSettingTabs);
	}

}
