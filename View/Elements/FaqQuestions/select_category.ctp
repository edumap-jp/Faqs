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

$currentCategoryId = isset($this->params['named']['category_id']) ? $this->params['named']['category_id'] : '0';

$options = array(
	'0' => array(
		'name' => __d('categories', 'Select Category'),
		'id' => null,
	),
);
$options = Hash::merge($options, Hash::combine($categories, '{n}.category.id', '{n}.category'));
?>

<span class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo h($options[$currentCategoryId]['name']); ?>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu">
		<?php foreach ($options as $key => $category) : ?>
			<li<?php echo ((int)$key === (int)$currentCategoryId ? ' class="active"' : ''); ?>>
				<?php echo $this->Html->link($category['name'],
						Hash::merge($url, array('category_id' => $category['id']))
					); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</span>
