<?php
/**
 * Element of Question delete form
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Form->create('FaqQuestion', array(
			'type' => 'delete',
			'controller' => 'faq_questions',
			'action' => 'delete/' . Current::read('Frame.id') . '/' . h($this->data['FaqQuestion']['key'])
		)); ?>

	<?php echo $this->Form->hidden('Frame.id'); ?>
	<?php echo $this->Form->hidden('Block.id'); ?>

	<?php echo $this->Form->hidden('Faq.key'); ?>
	<?php echo $this->Form->hidden('FaqQuestion.id'); ?>
	<?php echo $this->Form->hidden('FaqQuestion.faq_id'); ?>
	<?php echo $this->Form->hidden('FaqQuestion.key'); ?>

	<?php echo $this->Button->delete('',
			sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('faqs', 'Question'))
		); ?>
<?php echo $this->Form->end();
