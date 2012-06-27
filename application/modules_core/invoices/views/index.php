<?php $this->load->view('header', array('header_insert'=>array('jquery_date_picker', 'invoices/jquery_client_ac'))); ?>

<?php echo modules::run('invoices/widgets/generate_dialog'); ?>

<div class="grid_8" id="content_wrapper">
	<?php if (!uri_assoc('is_quote')) { ?>
	
	<!-- search form -->
	<div class="section_wrapper" id="invoice_search" style="display: none;">

		<form method="post" action="<?php echo site_url('invoice_search'); ?>" style="display: inline;">
			<input type="hidden" name="output_type" value="index" />

			<h3 class="title_black"><?php echo $this->lang->line('search'); ?></h3>

			<div class="content toggle" style="min-height: 0px; height: 30px;">

				<table>
					<tr>
						<td>
							<label><?php echo $this->lang->line('invoice_number'); ?>: 
							<input style="width: 90px;" type="text" name="invoice_number" /></label>
						</td>
						<td>
							<label><?php echo $this->lang->line('from_date'); ?>: 
							<input style="width: 90px;" type="text" class="datepicker" name="from_date" /></label>
						</td>
						<td>
							<label><?php echo $this->lang->line('to_date'); ?>:
							<input style="width: 90px;" type="text" class="datepicker" name="to_date" /></label>
						</td>
						<td>
							<label style="height: 20px;">&nbsp;
							<input type="submit" id="btn_submit_search" name="btn_submit_search" class="uibutton"  value="<?php echo $this->lang->line('save'); ?>" /></label>
						</td>
					</tr>
				</table>

                <div style="clear: both;">&nbsp;</div>

			</div>

		</form>

	</div>
	<?php } ?>

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo (!uri_assoc('is_quote') ? $this->lang->line('invoices') : $this->lang->line('quotes')); ?>
				<?php 
					if(isset($tot_num_invoices)) {
						?>
						<span style="font-size: 11px; font-color: white; padding-left: 5px;">( <?php echo $this->lang->line('found') . ' ' . $tot_num_invoices; ?> )</span>
						<?php 
					}
				?>
		</h3>

		<div class="content toggle no_padding">

			<?php 
					echo modules::run('invoices/display_invoice_table', $invoices, FALSE, TRUE); 
			?>

		</div>

	</div>

</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>