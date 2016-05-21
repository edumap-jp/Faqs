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

echo $this->NetCommonsHtml->script('/faqs/js/faqs.js');
?>

<div class="nc-content-list" ng-controller="FaqIndex">
	<article>
		<?php echo $this->NetCommonsHtml->blockTitle($faq['name']); ?>

		<hr>
		<?php echo $this->element('FaqQuestions/article', array(
				'faqQuestion' => $faqQuestion,
			)); ?>

		<hr>
	</article>
</div>
