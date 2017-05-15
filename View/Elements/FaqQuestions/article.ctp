<?php
/**
 * Element of question and answer
 *
 * #### second argument of $this->element()
 * - $faqQuestion: A result data of FaqQestion->getFaqQuestions()
 *     - faqQuestion:
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$answerKey = 'faq-answer-' . Current::read('Frame.id') . '-' . $faqQuestion['FaqQuestion']['id'];
$hidden = $this->params['action'] === 'index' ? 'hidden' : '';
?>

<article>
	<h3>
		<a href="<?php echo $this->NetCommonsHtml->url(array('action' => 'view', 'key' => $faqQuestion['FaqQuestion']['key'])); ?>"
			onclick="return false;"
			ng-click="displayAnswer('#<?php echo $answerKey; ?>')">

			<span class="glyphicon glyphicon-question-sign"> </span>
			<?php echo h($faqQuestion['FaqQuestion']['question']); ?>
		</a>

		<?php echo $this->Workflow->label($faqQuestion['FaqQuestion']['status']); ?>
	</h3>

	<div id="<?php echo $answerKey; ?>"
			class="<?php echo $hidden; ?>">

		<?php if ($faqQuestion['Category']['id']) : ?>
			<div class="text-muted faqs-category">
				<?php echo __d('categories', 'Category'); ?>:
				<?php echo $this->NetCommonsHtml->link($faqQuestion['CategoriesLanguage']['name'],
						array('action' => 'index', 'category_id' => $faqQuestion['Category']['id'])
					); ?>
			</div>
		<?php endif; ?>

		<article class="clearfix">
			<?php echo $faqQuestion['FaqQuestion']['answer']; ?>
		</article>

		<footer class="clearfix">
			<div class="pull-left">
				<?php echo $this->Like->buttons('FaqQuestion', $faqSetting, $faqQuestion); ?>
			</div>

			<?php if ($this->Workflow->canEdit('FaqQuestion', $faqQuestion)) : ?>
				<div class="pull-right">
					<?php echo $this->Button->editLink('', array('key' => $faqQuestion['FaqQuestion']['key']), array(
							'tooltip' => true,
							'iconSize' => 'btn-xs'
						)); ?>
				</div>
			<?php endif; ?>
		</footer>
	</div>
</article>
