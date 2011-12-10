<?php $this->load->view('dashboard/header', array('header_insert'=>array('dashboard/jquery_hover_links', 'inventory/jquery_stock_adjustment'))); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('inventory_items'); ?>
		<span style="font-size: 60%;">
		<?php $this->load->view('dashboard/btn_add', array('btn_value'=>$this->lang->line('add'))); ?>
		</span>
		</h3>

		<div class="content toggle no_padding">

			<table style="width: 100%;" class="hover_links">
				<tr>
					<th scope="col" class="first" style="width: 10%;"><?php echo $this->lang->line('id'); ?></th>
                    <th scope="col" style="width: 25%;"><?php echo $this->lang->line('type'); ?></th>
					<th scope="col" style="width: 25%;"><?php echo $this->lang->line('item'); ?></th>
                    <th scope="col" style="width: 12%;" class="col_amount"><?php echo $this->lang->line('stock'); ?></th>
					<th scope="col" class="col_amount last" style="width: 12%;"><?php echo $this->lang->line('price'); ?></th>
				</tr>
				<?php foreach ($items as $item) { ?>
				<tr id="inventory_item_<?php echo $item->inventory_id; ?>" class="hoverall">
					<td class="first"><?php echo $item->inventory_id; ?></td>
                    <td><?php echo $item->inventory_type; ?></td>
					<td><?php echo $item->inventory_name; ?></td>
                    <td id="stock_td_<?php echo $item->inventory_id; ?>" class="col_amount">
                        <?php if ($item->inventory_track_stock) { ?>
                        <a href="javascript:void(0)" class="stock_adjust_link" id="<?php echo $item->inventory_id; ?>" title="<?php echo $this->lang->line('adjust_stock'); ?>"><?php echo format_number($item->inventory_stock); ?></a>
                        <?php } else { ?>
                        --
                        <?php } ?>
                    </td>
					<td class="col_amount last"><?php echo display_currency($item->inventory_unit_price); ?></td>
				</tr>
				<tr class="actions" id="actions_inventory_item_<?php echo $item->inventory_id; ?>" style="display: none;">
					<td colspan="6" style="text-align: right;" class="last"><?php echo icon('arrow_up'); ?>
						<?php echo anchor('inventory/form/inventory_id/' . $item->inventory_id, $this->lang->line('edit')); ?> |
						<?php echo anchor('inventory/delete/inventory_id/' . $item->inventory_id, $this->lang->line('delete'), array("onclick"=>"javascript:if(!confirm('" . $this->lang->line('confirm_delete') . "')) return false")); ?>
					</td>
				</tr>
				<?php } ?>
			</table>

			<?php if ($this->mdl_inventory->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_inventory->page_links; ?>
			</div>
			<?php } ?>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'inventory/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>