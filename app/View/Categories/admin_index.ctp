<?php $this->Html->addCrumb(__('Categories'), '/admin/categories'); ?>
<div class="categories index">
	<h1><?php echo __('Categories'); ?></h1>
	<p>
		<?php echo $this->Html->link(__('New Category'),
					array('action' => 'add'), array('class'	 => 'btn')); ?>
	</p>
	<hr/>
	<table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-condensed">
		<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('category_order', __('sort.order')); ?></th>
			<th><?php echo $this->Paginator->sort('category'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('accession', __('Accession')); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ( $categories as $category ):
				$class = null;
				if ( $i++ % 2 == 0 ) {
					$class = ' class="altrow"';
				}
				?>
				<tr<?php echo $class; ?>>
					<td><?php echo $category['Category']['id']; ?>&nbsp;</td>
					<td><?php echo $category['Category']['category_order']; ?>&nbsp;</td>
					<td><?php echo $category['Category']['category']; ?>&nbsp;</td>
					<td><?php echo $category['Category']['description']; ?>&nbsp;</td>
					<td><?php echo $category['Category']['accession']; ?>&nbsp;</td>
					<td class="actions">
						<?php
						echo $this->Html->link(__('Edit'),
								array( 'action' => 'edit', $category['Category']['id'] ),
								array( 'class' => 'btn' ));
						?>
						<?php
						echo $this->Html->link(__('Delete'),
								array( 'action' => 'delete', $category['Category']['id'] ),
								array(
								'class' => 'btn',
								)
						);
						?>
					</td>
				</tr>
			<?php endforeach; ?>
	</table>
	<p>
		<span class="label label-info"><?= __('Accession') ?></span> <?= __('accession.exp') ?>
	</p>
</div>