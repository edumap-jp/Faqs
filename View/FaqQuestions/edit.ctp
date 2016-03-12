<?php
/**
 * Question edit form
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->script(array(
	'/faqs/js/faqs.js'
));

$faqQuestion = array();
$faqQuestion['answer'] = $this->data['FaqQuestion']['answer'];
?>

<div class="nc-content-list" ng-controller="FaqQuestions"
	ng-init="initialize(<?php echo h(json_encode(array('faqQuestion' => $faqQuestion))); ?>)">

	<article>
		<h1>
			<?php echo h($faq['name']); ?>
		</h1>

		<div class="panel panel-default">
			<?php echo $this->NetCommonsForm->create('FaqQuestion'); ?>
				<div class="panel-body">
					<?php echo $this->element('FaqQuestions/edit_form'); ?>

					<hr />

					<?php echo $this->Workflow->inputComment('FaqQuestion.status'); ?>
				</div>

				<?php echo $this->Workflow->buttons('FaqQuestion.status'); ?>
			<?php echo $this->NetCommonsForm->end(); ?>

			<?php if ($this->request->params['action'] === 'edit' && $this->Workflow->canDelete('FaqQuestion', $this->data)) : ?>
				<div class="panel-footer text-right">
					<?php echo $this->element('FaqQuestions/delete_form'); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php echo $this->Workflow->comments(); ?>
	</article>
</div>
