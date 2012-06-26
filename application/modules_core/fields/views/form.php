<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('custom_field_form'); ?></h3>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<div class="left_box">
				<?php if (!$field_id) { ?>
				<dl>
					<dt><label>* <?php echo $this->lang->line('object'); ?>: </label></dt>
					<dd>
						<select name="object_id">
							<option value=""><?php echo $this->lang->line('select'); ?></option>
							<?php foreach ($objects as $key=>$object) { ?>
							<option value="<?php echo $key; ?>" <?php if ($key == $this->mdl_fields->form_value('object_id')) { ?>selected="selected"<?php } ?>><?php echo $object; ?></option>
							<?php } ?>
						</select>
					</dd>
				</dl>
				<?php } else { ?>

				<dl>
					<dt><label><?php echo $this->lang->line('object'); ?>: </label></dt>
					<dd>
						<input type="hidden" name="object_id" value="<?php echo $this->mdl_fields->form_value('object_id'); ?>" />
						<?php echo $objects[$this->mdl_fields->form_value('object_id')]; ?>
					</dd>
				</dl>

				<?php } ?>
				</div>
				
				<div class="left_box">
				<dl>
					<dt><label>* <?php echo $this->lang->line('field_name'); ?>: </label></dt>
					<dd><input type="text" name="field_name" id="field_name" value="<?php echo $this->mdl_fields->form_value('field_name'); ?>" /></dd>
				</dl>
				</div>

				<div style="margin-top: 0px; float: right; margin-right: 5px; margin-top: 0px;">
					<input class="uibutton" type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('save'); ?>" />
					<input class="uibutton" type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
				</div>
				    				
                <div style="clear: both;">&nbsp;</div>

			</form>

		</div>

	</div>

</div>
<?php echo $actions_panel; ?>
<?php $this->load->view('footer'); ?>