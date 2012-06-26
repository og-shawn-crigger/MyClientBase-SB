<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $page_title; ?></h3>

		<div class="content toggle">

			<?php if (!$dir_is_writable) { ?>

				<p><?php echo $this->lang->line('template_dir_not_writable'); ?></p>
				<p><?php echo $template_dir; ?></p>

			<?php } else { ?>

				<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<label><?php echo $this->lang->line('template_name'); ?>: </label>

				<br />

				<input type="text" name="template_name" id="template_name" value="<?php echo $this->mdl_templates->form_value('template_name'); ?>" />

				<?php if (uri_assoc('template_name')) { ?>

					<p>* <?php echo $this->lang->line('changing_template_name'); ?></p>

				<?php } ?>

				<br /><br />

				<label><?php echo $this->lang->line('template_content'); ?>: </label>

				<br />

				<textarea name="template_content" style="width: 730px; height: 650px;"><?php echo $this->mdl_templates->form_value('template_content'); ?></textarea>

				<div style="float: right; margin-top: 10px; margin-right: 10px;">
					<input class="uibutton" type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('save'); ?>" />
					<input class="uibutton" type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
				</div>
				
				<div style="clear: both;">&nbsp;</div>				
			</form>

			<?php } ?>

		</div>

	</div>

</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>