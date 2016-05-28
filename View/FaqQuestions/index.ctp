<?php
/**
 * FaqQuestions index
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>
<?php echo $this->NetCommonsHtml->script('/faqs/js/faqs.js'); ?>

<div class="nc-content-list" ng-controller="FaqIndex">
	<?php echo $this->NetCommonsHtml->blockTitle($faq['name']); ?>

	<div class="clearfix">
		<div class="pull-left">
			<?php if ($categories) : ?>
				<?php echo $this->Category->dropDownToggle(array(
						'empty' => true,
						'displayMenu' => true,
					)); ?>
			<?php endif; ?>
		</div>
		<div class="pull-right">
			<?php if (Current::permission('content_editable') && $faqQuestions) : ?>
				<?php echo $this->LinkButton->sort('',
						$this->NetCommonsHtml->url(array('controller' => 'faq_question_orders', 'action' => 'edit'))
					); ?>
			<?php endif; ?>

			<?php echo $this->Workflow->addLinkButton('', null, array('tooltip' => __d('faqs', 'Create question'))); ?>
		</div>
	</div>

	<hr>
	<?php if ($faqQuestions) : ?>
		<?php foreach($faqQuestions as $faqQuestion): ?>
			<?php echo $this->element('FaqQuestions/article', array(
					'faqQuestion' => $faqQuestion,
				)); ?>

			<hr>
		<?php endforeach; ?>
	<?php else : ?>
		<?php echo __d('faqs', 'No faq found.') ?>
	<?php endif; ?>

</div>