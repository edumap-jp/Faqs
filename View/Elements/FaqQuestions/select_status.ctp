<?php
/**
 * 後で削除
 * Select element of faq questions index
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$url = Hash::merge(
	array('controller' => 'faq_questions', 'action' => 'index', $frameId),
	$this->params['named']
);

$curretStatus = isset($this->params['named']['status']) ? $this->params['named']['status'] : '';

$options = array(
	'FaqQuestion.status_' => array(
		'label' => __d('net_commons', 'Display all'),
		'status' => '',
	),
	'FaqQuestion.status_' . NetCommonsBlockComponent::STATUS_PUBLISHED => array(
		'label' => __d('net_commons', 'Published'),
		'status' => NetCommonsBlockComponent::STATUS_PUBLISHED,
	),
	'FaqQuestion.status_' . NetCommonsBlockComponent::STATUS_IN_DRAFT => array(
		'label' => __d('net_commons', 'In draft'),
		'status' => NetCommonsBlockComponent::STATUS_IN_DRAFT,
	),
	'FaqQuestion.status_' . NetCommonsBlockComponent::STATUS_APPROVED => array(
		'label' => __d('net_commons', 'Approving'),
		'status' => NetCommonsBlockComponent::STATUS_APPROVED,
	),
	'FaqQuestion.status_' . NetCommonsBlockComponent::STATUS_DISAPPROVED => array(
		'label' => __d('net_commons', 'Disapproving'),
		'status' => NetCommonsBlockComponent::STATUS_DISAPPROVED,
	),
);
?>

<span class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $options['FaqQuestion.status_' . $curretStatus]['label']; ?>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<?php foreach ($options as $key => $status) : ?>
			<li<?php echo ($status['status'] === $curretStatus ? ' class="active"' : ''); ?>>
				<?php echo $this->Html->link($status['label'],
						Hash::merge($url, array('status' => $status['status']))
					); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</span>
