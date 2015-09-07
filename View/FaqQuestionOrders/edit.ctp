<?php
/**
 * FaqQuestionOrders edit
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Html->script('/faqs/js/faqs.js'); ?>

<div ng-controller="FaqQuestionOrders" class="nc-content-list"
	 ng-init="initialize(<?php echo h(json_encode(['faqQuestions' => $faqQuestions])); ?>)">

	<article>
		<h1>
			<small><?php echo h($faq['name']); ?></small>
		</h1>

		<?php echo $this->Form->create('FaqQuestionOrder', array('novalidate' => true)); ?>
			<?php $this->Form->unlockField('FaqQuestionOrders'); ?>

			<?php echo $this->Form->hidden('Block.id', array(
					'value' => $blockId,
				)); ?>

			<?php echo $this->Form->hidden('Block.key', array(
					'value' => $blockKey,
				)); ?>

			<?php echo $this->Form->hidden('Faq.id', array(
					'value' => $faq['id'],
				)); ?>

			<?php echo $this->Form->hidden('Faq.key', array(
					'value' => $faq['key'],
				)); ?>

			<div ng-hide="faqQuestions.length">
				<p><?php echo __d('net_commons', 'Not found.'); ?></p>
			</div>

			<table class="table table-condensed" ng-show="faqQuestions.length">
				<thead>
					<tr>
						<th></th>
						<th>
							<?php echo $this->Paginator->sort('FaqQuestion.question', __d('faqs', 'Question')); ?>
						</th>
						<th>
							<?php echo $this->Paginator->sort('CategoryOrder.weight', __d('categories', 'Category')); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="q in faqQuestions track by $index">
						<td>
							<button type="button" class="btn btn-default btn-sm"
									ng-click="move('up', $index)" ng-disabled="$first">
								<span class="glyphicon glyphicon-arrow-up"></span>
							</button>

							<button type="button" class="btn btn-default btn-sm"
									ng-click="move('down', $index)" ng-disabled="$last">
								<span class="glyphicon glyphicon-arrow-down"></span>
							</button>

							<input type="hidden" name="data[FaqQuestionOrders][{{$index}}][FaqQuestionOrder][id]" ng-value="q.faqQuestionOrder.id">
							<input type="hidden" name="data[FaqQuestionOrders][{{$index}}][FaqQuestionOrder][faq_key]" ng-value="q.faqQuestionOrder.faqKey">
							<input type="hidden" name="data[FaqQuestionOrders][{{$index}}][FaqQuestionOrder][faq_question_key]" ng-value="q.faqQuestionOrder.faqQuestionKey">
							<input type="hidden" name="data[FaqQuestionOrders][{{$index}}][FaqQuestionOrder][weight]" ng-value="{{$index + 1}}">
						</td>
						<td>
							{{q.faqQuestion.question}}
						</td>
						<td>
							{{q.category.name}}
						</td>
					</tr>
				</tbody>
			</table>

			<div class="text-center">
				<button type="button" class="btn btn-default btn-workflow" onclick="location.href = '/<?php echo $cancelUrl; ?>'">
					<span class="glyphicon glyphicon-remove"></span>
					<?php echo __d('net_commons', 'Cancel'); ?>
				</button>

				<?php echo $this->Form->button(__d('net_commons', 'OK'), array(
						'class' => 'btn btn-primary btn-workflow',
						'name' => 'save',
					)); ?>
			</div>

		<?php echo $this->Form->end(); ?>
	</article>
</div>
