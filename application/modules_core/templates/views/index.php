<?php $this->load->view('dashboard/header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $page_title; ?></h3>

		<div class="content toggle no_padding">

			<?php if (!$dir_is_writable) { ?>

				<p><?php echo $this->lang->line('template_dir_not_writable'); ?></p>
				<p><?php echo $template_dir; ?></p>

			<?php } else { ?>

				<table style="width: 100%;">
					<tr>
						<th scope="col" class="first"><?php echo $this->lang->line('template_name'); ?></th>
						<th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
					</tr>
					<?php foreach ($templates as $template) { ?>
					<tr class="hoverall">
						<td class="first"><?php echo $template; ?></td>
						<td class="last">
							<a href="<?php echo site_url('templates/form/type/' . uri_assoc('type') . '/template_name/' . $template); ?>" title="<?php echo $this->lang->line('edit'); ?>">
								<?php echo icon('edit'); ?>
							</a>
							<a href="<?php echo site_url('templates/delete/type/' . uri_assoc('type') . '/template_name/' . $template); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">
								<?php echo icon('delete'); ?>
							</a>
						</td>
					</tr>
					<?php } ?>
				</table>

			<?php } ?>

		</div>

	</div>

</div>

<?php //$this->load->view('dashboard/sidebar'); ?>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>


<?php $this->load->view('dashboard/footer'); ?>