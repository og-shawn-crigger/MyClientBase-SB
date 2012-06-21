<?php $this->load->view('dashboard/header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper" style="background-color: #ff9000;">

		<h3 class="title_black"><?php echo $this->lang->line('invoice_groups'); ?></h3>

		<div class="content toggle no_padding">

			<table style="width: 100%;">
				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('group_prefix'); ?></th>
					<th scope="col"><?php echo $this->lang->line('group_next_id'); ?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
				</tr>
				<?php foreach ($invoice_groups as $invoice_group) { ?>
				<tr class="hoverall">
					<td class="first"><?php echo $invoice_group->invoice_group_name; ?></td>
					<td><?php echo $invoice_group->invoice_group_prefix; ?></td>
					<td><?php echo $invoice_group->invoice_group_next_id; ?></td>
					<td class="last">
						<a href="<?php echo site_url('invoices/invoice_groups/form/invoice_group_id/' . $invoice_group->invoice_group_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
							<?php echo icon('edit'); ?>
						</a>
						<a href="<?php echo site_url('invoices/invoice_groups/delete/invoice_group_id/' . $invoice_group->invoice_group_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
							<?php echo icon('delete'); ?>
						</a>
					</td>
				</tr>
				<?php } ?>
			</table>

			<?php if ($this->mdl_invoice_groups->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_invoice_groups->page_links; ?>
			</div>
			<?php } ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>array('invoices/invoice_group_sidebar'))); ?>

<?php $this->load->view('dashboard/footer'); ?>