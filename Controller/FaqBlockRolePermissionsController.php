<?php
/**
 * BlockRolePermissions Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqsAppController', 'Faqs.Controller');

/**
 * BlockRolePermissions Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Controller
 */
class FaqBlockRolePermissionsController extends FaqsAppController {

/**
 * layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * use models
 *
 * @var array
 */
	public $uses = array(
		'Faqs.Faq',
		'Faqs.FaqSetting'
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'blockPermissionEditable' => array('edit')
			),
		),
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Blocks.BlockRolePermissionForm'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		//タブの設定
		$this->initTabs('block_index', 'role_permissions');
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		if (! $faq = $this->Faq->getFaq($this->params['pass'][1], $this->viewVars['roomId'])) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('blockId', $faq['Block']['id']);
		$this->set('blockKey', $faq['Block']['key']);

		$permissions = $this->NetCommonsBlock->getBlockRolePermissions(
			$this->viewVars['blockKey'],
			['content_creatable', 'content_publishable']
		);
		$this->set('roles', $permissions['Roles']);

		if ($this->request->isPost()) {
			if ($this->FaqSetting->saveFaqSetting($this->request->data)) {
				$this->redirect('/faqs/faq_blocks/index/' . $this->viewVars['frameId']);
				return;
			}
			$this->handleValidationError($this->FaqSetting->validationErrors);

		} else {
			$this->request->data['FaqSetting'] = $faq['FaqSetting'];
			$this->request->data['Block'] = $faq['Block'];
			$this->request->data['BlockRolePermission'] = $permissions['BlockRolePermissions'];
		}
	}
}
