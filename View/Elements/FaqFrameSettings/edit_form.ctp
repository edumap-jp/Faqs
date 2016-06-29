<?php
/**
 * 表示方法変更 element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('FaqFrameSetting.frame_key'); ?>

<?php echo $this->DisplayNumber->select('FaqFrameSetting.content_per_page', array(
		'label' => __d('net_commons', 'Display the number of each page'),
	));
