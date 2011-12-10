<?php $this->load->view('dashboard/header', array('header_insert'=>'dashboard/jquery_hover_links')); ?>

<?php echo modules::run('invoices/widgets/generate_dialog'); ?>

<div class="grid_10" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo (!uri_assoc('is_quote') ? $this->lang->line('invoices') : $this->lang->line('quotes')); ?>
		<span style="font-size: 60%;">
			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" style="display: inline;">
				<input type="submit" name="<?php echo (!uri_assoc('is_quote')) ? 'btn_add_invoice' : 'btn_add_quote'; ?>" class="uibutton" style="float: right; margin-top: 10px; margin-right: 10px;" value="<?php echo (!uri_assoc('is_quote')) ? $this->lang->line('create_invoice') : $this->lang->line('create_quote'); ?> " />
				<input type="submit" name="btn_calendar_view" class="uibutton" style="float: right; margin-top: 10px; margin-right: 10px;" value="<?php echo $this->lang->line('calendar_view'); ?>" />
			</form>
		</span>
		</h3>

		<div class="content toggle no_padding">

			<?php $this->load->view('dashboard/system_messages'); ?>

			<?php echo modules::run('invoices/display_invoice_table', $invoices, FALSE, TRUE); ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>