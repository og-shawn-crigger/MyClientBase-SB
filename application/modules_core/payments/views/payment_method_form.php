<?php $this->load->view('header'); ?>

<div class="container_10" id="center_wrapper">

	<div class="grid_8" id="content_wrapper">

		<div class="section_wrapper">

			<h3 class="title_black"><?php echo $this->lang->line('payment_method_form'); ?></h3>

			<div class="content toggle">

				<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<dl>
					<dt><label>* <?php echo $this->lang->line('payment_method'); ?>: </label></dt>
					<dd><input type="text" name="payment_method" id="payment_method" value="<?php echo $this->mdl_payment_methods->form_value('payment_method'); ?>" /></dd>
				</dl>

				<div style="float: right; margin-right: 10px; margin-top: 10px;">
					<input class="uibutton" type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('save'); ?>" />
					<input class="uibutton" type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
				</div>
				
				<div style="clear: both;">&nbsp;</div>
							             
				</form>

			</div>

		</div>

	</div>
</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>