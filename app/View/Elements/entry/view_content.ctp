<?php
	### setup ###
	if (!isset($last_action)) $last_action = null;
	###
?>

		<h2 class="postingheadline">
			<?php
				$subject = $this->EntryH->getSubject($entry);
				// only make subject a link if it is not in entries/view
//				debug($this->request->action); debug($last_action); debug($isAjax);
				if ($this->request->action !== 'preview' && ( $this->request->is('ajax') || $this->request->action === 'mix')) {
					echo $this->Html->link(
							$subject,
							'/entries/view/'
							// is not set/unknown when showing preview
							. ((isset($entry['Entry']['id'])) ? $entry['Entry']['id'] : null),
							array(
									'class' => 'span_post_type',
									'escape'	=> false,
							)
						);
				} else {
					echo $subject;
				}
			?>
      –
      <span class="thread_line-username">
        <?php  if ($CurrentUser->isLoggedIn()) : ?>
          <?php echo  $this->Html->link(
								$entry['User']['username'],
								'/users/view/' . $entry['User']['id']
					); ?>
        <?php  else : ?>
          <?php echo  $entry['User']['username'] ?>
        <?php  endif; ?>
      </span>
			<?php  if ($entry['Entry']['pid'] == 0) : ?>
        <span class='category_acs_<?php echo $entry['Category']['accession']; ?>'
            title="<?php echo $entry['Category']['description']; ?> (<?php echo __d('nondynamic', 'category_acs_'.$entry['Category']['accession'].'_exp'); ?>)">
        (<?php echo $entry['Category']['category']; ?>)
        </span>
			<?php  endif; ?>
		</h2>
		<div class="author">
			<?php  if ($CurrentUser->isLoggedIn()) : ?>
				<?php echo   (!empty($entry['User']['user_place'])) ? $entry['User']['user_place'].',' : '' ;  ?>
			<?php  endif; ?>

        <?php /* <span title="<?php echo $this->TimeH->formatTime($entry['Entry']['time']); ?>"><?php echo $this->TimeH->formatTime($entry['Entry']['time'], 'glasen'); ?></span>, */ ?>
        <?php
					echo $this->TimeH->formatTime($entry['Entry']['time']);
					if (isset($entry['Entry']['edited_by']) && !is_null($entry['Entry']['edited_by'])
							&& strtotime($entry['Entry']['edited']) > strtotime($entry['Entry']['time']) + ( Configure::read('Saito.Settings.edit_delay') * 60 )
					):
						echo ' – ';
						echo __('%s edited by %s',
								array(
									$this->TimeH->formatTime($entry['Entry']['edited']),
									$entry['Entry']['edited_by']
								)
						);
						echo ',';
					else :
						echo ',';
					endif;
					echo ' ' . __('views_headline') . ': ' . $entry['Entry']['views'];

					echo '<span class="posting-badges">';
					echo $this->EntryH->getBadges($entry);
					echo '</span>';

					if (Configure::read('Saito.Settings.store_ip') && $CurrentUser->isMod()) {
						echo ', IP: ' . $entry['Entry']['ip'];
					}
				?>
		</div>

		<div class='posting'> <?php echo $this->Bbcode->parse($entry['Entry']['text']); ?> </div>