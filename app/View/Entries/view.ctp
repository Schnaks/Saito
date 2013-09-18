<?php
	Stopwatch::start('view.ctp');

  $this->start('headerSubnavLeft');
		echo $this->Html->link(
			'<i class="icon-arrow-left"></i> ' . __('back_to_forum_linkname'),
			$this->EntryH->getPaginatedIndexPageId(
				$entry['Entry']['tid'],
				$lastAction
			),
			[
				'class'  => 'textlink',
				'escape' => false,
				'rel'    => 'nofollow'
			]
		);
  $this->end();

	$this->append('meta');
		if (empty($entry['Entry']['text'])) {
			echo $this->Html->tag(
				'meta',
				null,
				['name' => 'description', 'content' => $entry['Entry']['subject']]
			);
		}
	$this->end('meta');

	if (isset($level) === false) {
		$level = 0;
	}
	if ($show_answer) {
		$this->Html->scriptBlock(
			"$(window).load(function() { $('#forum_answer_" . $entry['Entry']['id'] . "').trigger('click'); });",
			['inline' => false]
		);
	}
?>
<div class="entry view">
	<div class="box-content">
		<?=
			$this->element(
				'/entry/view_posting',
				['entry' => $entry, 'level' => $level]
			);
		?>
	</div>
	<?=
		$this->element(
			'entry/thread_cached_init',
			[
				'entries_sub'    => $tree,
				'level'          => 0,
				'toolboxButtons' => ['mix' => true]
			]
		); ?>
</div>
<?php Stopwatch::stop('view.ctp'); ?>
