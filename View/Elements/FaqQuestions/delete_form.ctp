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

<?php echo $this->NetCommonsForm->create('FaqQuestion', array(
			'type' => 'delete',
			'controller' => 'faq_questions',
			'action' => NetCommonsUrl::actionUrl(array(
				'controller' => $this->params['controller'],
				'action' => 'delete',
				'block_id' => Current::read('Block.id'),
				'frame_id' => Current::read('Frame.id'),
				'key' => h($this->data['FaqQuestion']['key'])
			))
		)); ?>

	<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
	<?php echo $this->NetCommonsForm->hidden('Block.id'); ?>

	<?php echo $this->NetCommonsForm->hidden('Faq.key'); ?>
	<?php echo $this->NetCommonsForm->hidden('FaqQuestion.id'); ?>
	<?php echo $this->NetCommonsForm->hidden('FaqQuestion.faq_id'); ?>
	<?php echo $this->NetCommonsForm->hidden('FaqQuestion.key'); ?>

	<?php echo $this->Button->delete('',
			sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('faqs', 'Question'))
		); ?>
<?php echo $this->NetCommonsForm->end();
