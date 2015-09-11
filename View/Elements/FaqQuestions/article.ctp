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

$viewUrl = $this->Html->url(array(
		'controller' => 'faq_questions',
		'action' => 'view',
		Current::read('Frame.id'),
		$faqQuestion['FaqQuestion']['key']
	));

//後で削除
//$editUrl = $this->Html->url(array(
//		'controller' => 'faq_questions',
//		'action' => 'edit',
//		Current::read('Frame.id'),
//		$faqQuestion['FaqQuestion']['key']
//	));

$answerKey = 'faq-answer-' . Current::read('Frame.id') . '-' . $faqQuestion['FaqQuestion']['id'];

$hidden = $this->params['action'] === 'index' ? 'hidden' : '';
?>

<article>
	<h2>
		<a href="<?php echo $viewUrl; ?>" onclick="return false;"
			ng-click="displayAnswer('#<?php echo $answerKey; ?>')">

			<span class="glyphicon glyphicon-question-sign"> </span>
			<?php echo h($faqQuestion['FaqQuestion']['question']); ?>
		</a>

		<small>
			<?php echo $this->Workflow->label($faqQuestion['FaqQuestion']['status']); ?>
		</small>
	</h2>

	<div id="<?php echo $answerKey; ?>"
			class="<?php echo $hidden; ?>">

		<div>
			<?php echo $faqQuestion['FaqQuestion']['answer']; ?>
		</div>

		<?php if ($this->Workflow->canEdit('FaqQuestion', $faqQuestion)) : ?>
			<div class="text-right">
				<?php echo $this->Button->editLink('', array('key' => $faqQuestion['FaqQuestion']['key']), array(
						'tooltip' => true,
						'iconSize' => 'xs'
					)); ?>
			</div>
		<?php endif; ?>
	</div>
</article>
