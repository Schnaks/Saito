<div id="upload_index" class="upload index">
	<div class="l-box-header box-header">
		<div>
			<div>
			</div>
			<div>
					<h2>Dateiübersicht</h2> <!-- @lo -->
			</div>
			<div class="c_last_child">
				&nbsp;
			</div>
		</div>
	</div><!-- header -->
	<div class="content">
			<?php if ( $isUploadAllowed ) : ?>
			<div class="upload_box" style="display: table;">
	<?php echo $this->Form->create('Upload',
			array( 'action' => 'add', 'type' => 'file', 'inputDefaults' => array( 'div' => false, 'label' => false ) )); ?>
				<div style="display: table-row">
					<div class="upload_box_header" style= display:table-cell;">
						<h2><?php echo __('upload_new_title'); ?></h2>
						<br/>
									(max. <?php echo $this->Number->toReadableSize(Configure::read('Saito.Settings.upload_max_img_size') * 1024); ?> jpg, jpeg, png, gif)
					</div>
				</div>
				<div style="display: table-row;">
					<div class="upload_box_footer" style="display: table-cell; ">
						<div style="position: relative;">
							<?php
								// To present a nice upload button we generate a dead button.
								// Beneath the nice dummy button is the actual input file upload,
								// but it's hidden behind the opacity:0 curtain div.
							  // z-index: 2000 to have the button above the jQuery UI modal.
								echo $this->Form->button(
									__("upload_btn"),
									array( 'class' => 'btn btn-submit', 'type' => 'button' ));
							?>
							<div style="position: absolute; z-index: 2000; top:0; right: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; overflow: hidden; " >
								<?php echo $this->FileUpload->input(
										array(
											'style' => 'width: 150px; height: 100%',
											'onchange' => 'this.form.submit();'
										)
							);
							?>
							</div>
						</div>
					</div>
				</div>
	<?php echo $this->Form->end(); ?>
			</div>
					<?php endif; ?>
<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix"></div>