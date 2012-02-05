<?php echo $html->docType('xhtml-trans'); ?>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $title_for_layout ?></title>
		<?php echo $html->charset(); ?>

		<?php # echo $html->script('jquery-1.6.1.min'); ?>
		<?php # echo $html->script('bootstrap/bootstrap'); ?>
		<?php echo $html->css('bootstrap/css/bootstrap.min.css'); ?>

	</head>
	<body>
		<div class="container">
			<div class="navbar">
				<div class="navbar-inner">
					<div class="container" style="width: auto;">
						<a class="brand" href="#">
							Saito
						</a>
						<ul class="nav">
							<li class="active">
								<?php
								echo $this->Html->link(__('Admin Settings', true),
										array( 'controller' => 'admins', 'action' => 'index', 'admin' => true ));
								?>
							</li>
							<li>
								<?php echo $html->link(__('Categories',
												true), '/admin/categories/index'); ?>
							</li>
						</ul>
						<ul class="nav pull-right">
							<li class="divider-vertical"></li>
							<li>
								<?php
								echo $this->Html->link(__('Forum', true),
										array( 'controller' => 'entries', 'action' => 'index', 'admin' => false ));
								?>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<?php
			$flashMessage = $this->Session->flash();
			$emailMessage = $this->Session->flash('email');
			if ( $flashMessage || $emailMessage ) :
				?>
				<div class="alert">
					<?php echo $flashMessage; ?>
					<?php echo $emailMessage; ?>
				</div>
			<?php endif; ?>

			<div class="row">
				<div class="span1">&nbsp;</div>
				<div class="span10">
					<?php echo $this->Html->getCrumbs(' > ',
							'Home'); ?>
					<?php echo $content_for_layout ?>
				</div>
				<div class="span1">&nbsp;</div>
			</div>
		</div>
		<?php echo $scripts_for_layout; ?>
		<?php echo $js->writeBuffer(); ?>
		<?php echo $this->element('sql_dump'); ?>
	</body>
</html>