<?php $this->load->view('dashboard/header', array('header_insert'=>array('dashboard/jquery_hover_links'))); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('inventory_types'); ?>
		<span style="font-size: 60%;">
		<?php $this->load->view('dashboard/btn_add', array('btn_value'=>$this->lang->line('add'))); ?>
		</span>
		</h3>

		<?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle no_padding">

			<table style="width: 100%;" class="hover_links">
				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('id'); ?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('inventory_type'); ?></th>
				</tr>
				<?php foreach ($inventory_types as $inventory_type) { ?>
				<tr id="inventory_type_<?php echo $inventory_type->inventory_type_id; ?>" class="hoverall">
					<td class="first"><?php echo $inventory_type->inventory_type_id; ?></td>
					<td class="last"><?php echo $inventory_type->inventory_type; ?></td>
				</tr>
				<tr class="actions" id="actions_inventory_type_<?php echo $inventory_type->inventory_type_id; ?>" style="display: none;">
					<td colspan="2" style="text-align: right;" class="last"><?php echo icon('arrow_up'); ?>
						<?php echo anchor('inventory/inventory_types/form/inventory_type_id/' . $inventory_type->inventory_type_id, $this->lang->line('edit')); ?> |
						<?php echo anchor('inventory/inventory_types/delete/inventory_type_id/' . $inventory_type->inventory_type_id, $this->lang->line('delete'), array("onclick"=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false")); ?>
					</td>
				</tr>

				<?php } ?>
			</table>

			<?php if ($this->mdl_inventory_types->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_inventory_types->page_links; ?>
			</div>
			<?php } ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>array('inventory/sidebar'))); ?>

<?php $this->load->view('dashboard/footer'); ?>