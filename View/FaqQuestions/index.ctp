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

	<header class="clearfix">
		<div class="pull-left">
			<?php if ($categories) : ?>
				<?php echo $this->Category->dropDownToggle(array(
						'empty' => true,
						'displayMenu' => true,
					)); ?>
			<?php endif; ?>

			<?php echo $this->DisplayNumber->dropDownToggle(); ?>
		</div>
		<div class="pull-right">
			<?php if (Current::permission('content_editable') && $faqQuestions) : ?>
				<?php echo $this->LinkButton->sort('',
						array('controller' => 'faq_question_orders', 'action' => 'edit')
					); ?>
			<?php endif; ?>

			<?php echo $this->Workflow->addLinkButton('', null, array('tooltip' => __d('faqs', 'Create question'))); ?>
		</div>
	</header>

	<?php if ($faqQuestions) : ?>
		<?php foreach($faqQuestions as $faqQuestion): ?>
			<?php echo $this->element('FaqQuestions/article', array(
					'faqQuestion' => $faqQuestion,
				)); ?>
		<?php endforeach; ?>

		<?php echo $this->element('NetCommons.paginator'); ?>

	<?php else : ?>
		<div class="nc-not-found">
			<?php echo __d('faqs', 'No faq found.') ?>
		</div>
	<?php endif; ?>

</div>
