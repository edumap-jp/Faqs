<?php
/**
 * block index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="block-setting-body">
	<?php echo $this->element('NetCommons.setting_tabs', $settingTabs); ?>

	<div class="tab-content">
		<div class="text-right">
			<?php echo $this->Button->addLink(); ?>
		</div>

		<?php echo $this->Form->create('', array('url' => '/frames/frames/edit/' . $this->data['Frame']['id'])); ?>

			<?php echo $this->Form->hidden('Frame.id'); ?>

			<table class="table table-hover">
				<thead>
					<tr>
						<th></th>
						<th>
							<?php echo $this->Paginator->sort('Block.name', __d('faqs', 'FAQ Name')); ?>
						</th>
						<th>
							<?php echo $this->Paginator->sort('Block.modified', __d('net_commons', 'Updated date')); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($faqs as $faq) : ?>
						<tr<?php echo ($this->data['Frame']['block_id'] === $faq['Block']['id'] ? ' class="active"' : ''); ?>>
							<td>
								<?php echo $this->NetCommonsForm->radio('Frame.block_id', array($faq['Block']['id'] => ''), array(
										'onclick' => 'submit()',
										'ng-click' => 'sending=true',
										'ng-disabled' => 'sending'
									)); ?>
							</td>
							<td>
								<?php echo $this->NetCommonsForm->editLink($faq['Block']['id'], $faq['Block']['name']); ?>
							</td>
							<td>
								<?php echo $this->Date->dateFormat($faq['Block']['modified']); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php echo $this->Form->end(); ?>

		<?php echo $this->element('NetCommons.paginator'); ?>
	</div>
</article>




