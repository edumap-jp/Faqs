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
<?php echo $this->NetCommonsHtml->script('/faqs/js/faqs.js', false); ?>

<div class="nc-content-list" ng-controller="FaqIndex">
	<article>
		<h1>
			<small>
				<?php echo h($faq['name']); ?>
			</small>
		</h1>

		<div class="clearfix">
			<div class="pull-left">
				<?php echo $this->Category->dropDownToggle(array(
						'empty' => true,
						'displayMenu' => true,
					)); ?>
			</div>
			<div class="pull-right">
				<?php if (Current::permission('content_editable')) : ?>
					<span class="nc-tooltip " tooltip="<?php echo __d('faqs', 'Sort question'); ?>">
						<a href="<?php echo $this->Html->url('/faqs/faq_question_orders/edit/' . Current::read('Frame.id')); ?>" class="btn btn-default">
							<span class="glyphicon glyphicon-sort"> </span>
						</a>
					</span>
				<?php endif; ?>
				<?php if (Current::permission('content_creatable')) : ?>
					<?php echo $this->Button->addLink('', null, array('tooltip' => __d('faqs', 'Create question'))); ?>
				<?php endif; ?>
			</div>
		</div>

		<hr>
		<?php foreach($faqQuestions as $faqQuestion): ?>
			<?php echo $this->element('FaqQuestions/article', array(
					'faqQuestion' => $faqQuestion,
				)); ?>

			<hr>
		<?php endforeach; ?>
	</article>
</div>
