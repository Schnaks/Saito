<?php
	$this->start('headerSubnavLeft');
	echo $this->Layout->navbarBack();
	$this->end();

	$this->element('users/menu');
?>
<div class="user index">

	<div class="panel">
		<?= $this->Layout->panelHeading($title_for_page, ['pageHeading' => true]) ?>
		<div class="panel-content">
			<div class="table-menu sort-menu">
				<?php
					$_sortBy = $this->Paginator->sort('username', __('username_marking'));
					$_sortBy .= ', ' . $this->Paginator->sort('User.user_type',
									__('user_type'));
					$_sortBy .= ', ' . $this->Paginator->sort('UserOnline.user_id',
									__('userlist_online'),
									[
											'direction' => 'desc'
									]);
					$_sortBy .= ', ' . $this->Paginator->sort('registered',
									__('registered'),
									[
											'direction' => 'desc'
									]);
					$_showBlocked = Configure::read('Saito.Settings.block_user_ui');
					if ($_showBlocked) {
						$_sortBy .= ', ' . $this->Paginator->sort('user_lock',
										__('user.set.lock.t'),
										['direction' => 'desc']);
					}
					echo __('Sort by: %s', $_sortBy);
				?>
			</div>
			<table class="table th-left row-sep">
				<tbody>
				<?php
					foreach ($users as $user): ?>
						<tr>
							<td>
								<?=
									$this->Html->link(
											$user['User']['username'],
											'/users/view/' . $user['User']['id']);
								?>
							</td>
							<td>
								<?php
									$_u = [
											$this->UserH->type($user['User']['user_type']),
											__('user_since %s',
													$this->TimeH->formatTime($user['User']['registered'],
															'%d.%m.%Y')),
									];
									if ($user['UserOnline']['logged_in']) {
										$_u[] = __('Online');
									}
									if ($_showBlocked && $user['User']['user_lock']) {
										$_u[] = __('%s banned',
												$this->UserH->banned($user['User']['user_lock']));
									}
									echo $this->Html->nestedList($_u);
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
