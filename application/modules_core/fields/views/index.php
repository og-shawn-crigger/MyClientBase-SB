<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('custom_fields'); ?></h3>

		<div class="content toggle no_padding">

			<table style="width: 100%;">
				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('object'); ?></th>
					<th scope="col"><?php echo $this->lang->line('field_name'); ?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
				</tr>
				<?php foreach ($fields as $field) { ?>
				<tr>
					<td class="first"><?php echo $objects[$field->object_id] . '.' . $field->column_name; ?></td>
					<td><?php echo $field->field_name; ?></td>
					<td class="last">
						<a href="<?php echo site_url('fields/form/field_id/' . $field->field_id); ?>" title="<?php echo $this->lang->line('edit'); ?>">
							<?php echo icon('edit'); ?>
						</a>
						<a href="<?php echo site_url('fields/delete/field_id/' . $field->field_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
							<?php echo icon('delete'); ?>
						</a>
					</td>
				</tr>
				<?php } ?>
			</table>

			<?php if ($this->mdl_fields->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_fields->page_links; ?>
			</div>
			<?php } ?>

		</div>

	</div>

</div>

<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>