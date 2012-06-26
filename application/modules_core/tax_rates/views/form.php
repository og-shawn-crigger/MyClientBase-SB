<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('tax_rate_form'); ?></h3>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">
				<div class="left_box">
				<dl>
					<dt><label style="width: 100px;">* <?php echo $this->lang->line('tax_rate_name'); ?>: </label></dt>
					<dd><input type="text" name="tax_rate_name" id="tax_rate_name" value="<?php echo $this->mdl_tax_rates->form_value('tax_rate_name'); ?>" /></dd>
				</dl>
				</div>
				<div class="right_box">
				<dl>
					<dt style="width: 150px;"><label>* <?php echo $this->lang->line('tax_rate_percent'); ?>: </label></dt>
					<dd><input  style="width: 50px;" type="text" name="tax_rate_percent" id="tax_rate_symbol" value="<?php echo $this->mdl_tax_rates->form_value('tax_rate_percent'); ?>" /></dd>
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