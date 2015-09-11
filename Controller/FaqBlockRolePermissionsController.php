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
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'edit' => 'block_permission_editable',
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
 * beforeRender
 *
 * @return void
 */
	public function beforeRender() {
		//タブの設定
		$this->initTabs('block_index', 'role_permissions');
		parent::beforeRender();
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		CurrentFrame::setBlock($this->request->params['pass'][1]);

		if (! $faq = $this->Faq->getFaq()) {
			$this->setAction('throwBadRequest');
			return false;
		}

		$permissions = $this->Workflow->getBlockRolePermissions(
			array('content_creatable', 'content_publishable')
		);
		$this->set('roles', $permissions['Roles']);

		if ($this->request->isPost()) {
			if ($this->FaqSetting->saveFaqSetting($this->request->data)) {
				$this->redirect(Current::backToIndexUrl('default_setting_action'));
				return;
			}
			$this->NetCommons->handleValidationError($this->FaqSetting->validationErrors);

		} else {
			$this->request->data['FaqSetting'] = $faq['FaqSetting'];
			$this->request->data['Block'] = $faq['Block'];
			$this->request->data['BlockRolePermission'] = $permissions['BlockRolePermissions'];
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}
}
