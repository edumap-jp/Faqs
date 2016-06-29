<?php
/**
 * Blocks edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->element('Blocks.form_hidden'); ?>

<?php echo $this->NetCommonsForm->hidden('Faq.id'); ?>
<?php echo $this->NetCommonsForm->hidden('Faq.key'); ?>
<?php echo $this->NetCommonsForm->hidden('Faq.language_id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqSetting.faq_key'); ?>

<?php echo $this->NetCommonsForm->input('Faq.name', array(
		'type' => 'text',
		'label' => __d('faqs', 'FAQ Name'),
		'required' => true
	)); ?>

<?php echo $this->element('Blocks.public_type'); ?>

<?php echo $this->Like->setting('FaqSetting.use_like', 'FaqSetting.use_unlike'); ?>

<?php echo $this->element('Categories.edit_form');
