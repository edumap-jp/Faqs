<?php
/**
 * Block edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="modal-body">
	<?php echo $this->element('NetCommons.setting_tabs', $settingTabs); ?>

	<div class="tab-content">
		<?php echo $this->element('Blocks.setting_tabs', $blockSettingTabs); ?>

		<?php echo $this->element('Blocks.edit_form', array(
				'model' => 'Faq',
				'callback' => 'Faqs.FaqBlocks/edit_form',
				'cancelUrl' => Current::backToIndexUrl('default_setting_action'),
			)); ?>

		<?php if ($this->request->params['action'] === 'edit') : ?>
			<?php echo $this->element('Blocks.delete_form', array(
					'model' => 'FaqBlock',
					'action' => 'delete/' . Current::read('Frame.id') . '/' . Current::read('Block.id'),
					'callback' => 'Faqs.FaqBlocks/delete_form'
				)); ?>
		<?php endif; ?>
	</div>
</div>
