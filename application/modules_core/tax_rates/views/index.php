<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('tax_rates'); ?></h3>

		<div class="content toggle no_padding">

			<table style="width: 100%;">
				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('tax_rate_name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('tax_rate_percent'); ?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
				</tr>
				<?php foreach ($tax_rates as $tax_rate) { ?>
				<tr class="hoverall">
					<td  class="first"><?php echo $tax_rate->tax_rate_name; ?></td>
					<td><?php echo format_number($tax_rate->tax_rate_percent, TRUE, $this->mdl_mcb_data->setting('decimal_taxes_num')); ?>%</td>
					<td class="last">
						<a href="<?php echo site_url('tax_rates/form/tax_rate_id/' . $tax_rate->tax_rate_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
							<?php echo icon('edit'); ?>
						</a>
						<a href="<?php echo site_url('tax_rates/delete/tax_rate_id/' . $tax_rate->tax_rate_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
							<?php echo icon('delete'); ?>
						</a>
					</td>
				</tr>
				<?php } ?>
			</table>

			<?php if ($this->mdl_tax_rates->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_tax_rates->page_links; ?>
			</div>
			<?php } ?>

		</div>

	</div>

</div>

<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>