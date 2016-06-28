<?php
/**
 * Element of Question edit form
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
<?php echo $this->NetCommonsForm->hidden('Block.id'); ?>
<?php echo $this->NetCommonsForm->hidden('Block.key'); ?>
<?php echo $this->NetCommonsForm->hidden('Faq.id'); ?>
<?php echo $this->NetCommonsForm->hidden('Faq.key'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestion.id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestion.faq_id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestion.block_id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestion.key'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestion.status'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestion.language_id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestionOrder.id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestionOrder.faq_key'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqQuestionOrder.faq_question_key'); ?>

<?php echo $this->Category->select('FaqQuestion.category_id', array('empty' => true)); ?>

<?php echo $this->NetCommonsForm->input('FaqQuestion.question', array(
		'type' => 'textarea',
		'label' => __d('faqs', 'Question'),
		'required' => true,
		'rows' => 2,
	)); ?>

<?php echo $this->NetCommonsForm->wysiwyg('FaqQuestion.answer', array(
		'label' => __d('faqs', 'Answer'),
		'required' => true,
	));
