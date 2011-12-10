<?php $this->load->view('dashboard/header'); ?>

<?php $this->load->view('dashboard/jquery_date_picker'); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('import'); ?></h3>

        <?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data">

				<dl>
					<dt><label><?php echo $this->lang->line('import_type'); ?>: </label></dt>
					<dd>
                        <select name="import_type">
                            <option value=""></option>
                            <option value="clients"><?php echo $this->lang->line('clients'); ?></option>
                        </select>
                    </dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('upload_file'); ?>: </label></dt>
					<dd><input type="file" name="userfile" size="20" /></dd>
				</dl>

				<input type="submit" id="btn_submit" name="btn_upload" value="<?php echo $this->lang->line('submit'); ?>" />

				<div style="clear: both;">&nbsp;</div>

			</form>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'invoices/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>