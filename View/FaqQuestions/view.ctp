<?php
/**
 * FaqQuestions view
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="nc-content-list">
	<header class="clearfix">
		<div class="pull-left">
			<?php echo $this->LinkButton->toList(); ?>
		</div>

		<?php if ($this->Workflow->canEdit('FaqQuestion', $faqQuestion)) : ?>
			<div class="pull-right">
				<?php echo $this->Button->editLink('', array('key' => $faqQuestion['FaqQuestion']['key'])); ?>
			</div>
		<?php endif; ?>
	</header>

	<h1 class="clearfix">
		<span class="glyphicon glyphicon-question-sign" aria-hidden="true"> </span>

		<?php
			echo $this->Workflow->label($faqQuestion['FaqQuestion']['status']) . '' .
					h($faqQuestion['FaqQuestion']['question']);
		?>
	</h1>

	<?php if ($faqQuestion['Category']['id']) : ?>
		<div class="text-muted">
			<?php echo __d('categories', 'Category'); ?>:
			<?php echo $this->NetCommonsHtml->link($faqQuestion['CategoriesLanguage']['name'],
					array('action' => 'index', 'category_id' => $faqQuestion['Category']['id'])
				); ?>
		</div>
	<?php endif; ?>

	<div>
		<?php echo $faqQuestion['FaqQuestion']['answer']; ?>
	</div>

	<footer>
		<?php echo $this->Like->buttons('FaqQuestion', $faqSetting, $faqQuestion); ?>
	</footer>
</article>
