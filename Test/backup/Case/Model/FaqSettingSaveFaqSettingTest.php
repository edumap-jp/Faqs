<?php
/**
 * Test of FaqSetting->saveFaqSetting()
 *
 * @property FaqSetting $FaqSetting
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FaqSettingTestBase', 'Faqs.Test/Case/Model');

/**
 * Test of FaqSetting->saveFaqSetting()
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Faqs\Test\Case\Model
 */
class FaqSettingSaveFaqSettingTest extends FaqSettingTestBase {

/**
 * Default save data
 *
 * @var array
 */
	private $__defaultData = array(
		'FaqSetting' => array(
			'id' => '1',
			'faq_key' => 'faq_1',
			'use_workflow' => true,
		),
		'Block' => array(
			'id' => '100',
			'key' => 'block_100'
		),
		'BlockRolePermission' => array(
			'content_creatable' => array(
				'general_user' => array(
					'id' => '100',
					'roles_room_id' => '4',
					'block_key' => 'block_100',
					'permission' => 'content_creatable',
					'value' => true,
				)
			),
			'content_publishable' => array(
				'chief_editor' => array(
					'id' => '101',
					'roles_room_id' => '2',
					'block_key' => 'block_100',
					'permission' => 'content_publishable',
					'value' => true,
				),
				'editor' => array(
					'id' => '102',
					'roles_room_id' => '3',
					'block_key' => 'block_100',
					'permission' => 'content_publishable',
					'value' => true,
				)
			)
		)
	);

/**
 * Expect to save the FaqSetting
 *
 * @return void
 */
	public function test() {
		//データ生成
		$data = Hash::merge($this->__defaultData, array());

		//処理実行
		$result = $this->FaqSetting->saveFaqSetting($data);
		$this->assertTrue($result);

		//成否のデータ取得
		$result = $this->FaqSetting->getFaqSetting($data['FaqSetting']['faq_key']);
		$blockRolePermissions = $this->BlockRolePermission->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'block_key' => $data['Block']['key']
				),
			)
		);
		$blockRolePermissions = Hash::combine(
			$blockRolePermissions,
			'{n}.BlockRolePermission.id',
			'{n}.BlockRolePermission'
		);
		$result['BlockRolePermission'] = $blockRolePermissions;

		//期待値の生成
		$expected = $data;
		$expected['BlockRolePermission'] = Hash::combine($expected['BlockRolePermission'], '{s}.{s}.id', '{s}.{s}');

		//テスト実施
		$this->_assertArray($expected['FaqSetting'], $result['FaqSetting'], 1);
		$this->_assertArray($expected['BlockRolePermission'], $result['BlockRolePermission'], 2);
	}

/**
 * Expect to validation error FaqSetting
 *
 * @return void
 */
	public function testFaqSettingValidationError() {
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'FaqSetting' => array(
				'faq_key' => '',
			),
		));

		//処理実行
		$result = $this->FaqSetting->saveFaqSetting($data);
		$this->assertFalse($result);

		$result = $this->FaqSetting->getFaqSetting($this->__defaultData['FaqSetting']['faq_key']);
		$blockRolePermissions = $this->BlockRolePermission->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'block_key' => $data['Block']['key']
				),
			)
		);
		$blockRolePermissions = Hash::combine(
			$blockRolePermissions,
			'{n}.BlockRolePermission.id',
			'{n}.BlockRolePermission'
		);
		$result['BlockRolePermission'] = $blockRolePermissions;

		//期待値の生成
		$expected = $this->__defaultData;
		$expected['BlockRolePermission'] = Hash::combine($expected['BlockRolePermission'], '{s}.{s}.id', '{s}.{s}');

		//テスト実施
		$this->_assertArray($expected['FaqSetting'], $result['FaqSetting'], 1);
		$this->_assertArray($expected['BlockRolePermission'], $result['BlockRolePermission'], 2);
	}

/**
 * Expect to validation error of BlockRolePermission
 *
 * @return void
 */
	public function testBlockRolePermissionValidationError() {
		//データ生成
		$data = Hash::merge($this->__defaultData, array(
			'BlockRolePermission' => array(
				'content_creatable' => array(
					'general_user' => array(
						'block_key' => '',
					)
				),
			)
		));

		//処理実行
		$result = $this->FaqSetting->saveFaqSetting($data);
		$this->assertFalse($result);

		$result = $this->FaqSetting->getFaqSetting($this->__defaultData['FaqSetting']['faq_key']);
		$blockRolePermissions = $this->BlockRolePermission->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'block_key' => $data['Block']['key']
				),
			)
		);
		$blockRolePermissions = Hash::combine(
			$blockRolePermissions,
			'{n}.BlockRolePermission.id',
			'{n}.BlockRolePermission'
		);
		$result['BlockRolePermission'] = $blockRolePermissions;

		//期待値の生成
		$expected = $this->__defaultData;
		$expected['BlockRolePermission'] = Hash::combine($expected['BlockRolePermission'], '{s}.{s}.id', '{s}.{s}');

		//テスト実施
		$this->_assertArray($expected['FaqSetting'], $result['FaqSetting'], 1);
		$this->_assertArray($expected['BlockRolePermission'], $result['BlockRolePermission'], 2);
	}

/**
 * Expect to fail on FaqSetting->save()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnSave() {
		$this->setExpectedException('InternalErrorException');

		$data = $this->__defaultData;

		$this->FaqSetting = $this->getMockForModel('Faqs.FaqSetting', array('save'));
		$this->FaqSetting->expects($this->any())
			->method('save')
			->will($this->returnValue(false));

		$this->FaqSetting->saveFaqSetting($data);
	}

/**
 * Expect to fail on BlockRolePermission->save()
 * e.g.) connection error
 *
 * @return  void
 */
	public function testFailOnBlockRolePermissionSave() {
		$this->setExpectedException('InternalErrorException');

		$data = $this->__defaultData;

		$this->BlockRolePermission = $this->getMockForModel('Blocks.BlockRolePermission', array('saveMany'));
		$this->BlockRolePermission->expects($this->any())
			->method('saveMany')
			->will($this->returnValue(false));

		$this->FaqSetting->saveFaqSetting($data);
	}

}
