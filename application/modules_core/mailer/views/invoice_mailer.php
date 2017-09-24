<?php $this->load->view('header'); ?>

<div class="grid_11" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('send_email'); ?></h3>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<dl>
					<dt><label>* <?php echo $this->lang->line('invoice_template'); ?>: </label></dt>
					<dd>
						<select name="invoice_template">
							<?php foreach ($templates as $template) { ?>
							<option <?php if ($this->mdl_mailer->form_value('invoice_template') == $template) { ?>selected="selected"<?php } ?>><?php echo $template; ?></option>
							<?php } ?>
						</select>
					</dd>
				</dl>

				<dl>
					<dt><label>* <?php echo $this->lang->line('from_name'); ?>: </label></dt>
					<dd><input type="text" name="email_from_name" value="<?php echo $this->mdl_mailer->form_value('email_from_name'); ?>" /></dd>
				</dl>
				<dl>
					<dt><label>* <?php echo $this->lang->line('from_email'); ?>: </label></dt>
					<dd><input type="text" name="email_from_email" value="<?php echo $this->mdl_mailer->form_value('email_from_email'); ?>" /></dd>
				</dl>
				<dl>
					<dt><label>* <?php echo $this->lang->line('to'); ?>: </label></dt>
					<dd><input type="text" name="email_to" value="<?php echo $this->mdl_mailer->form_value('email_to'); ?>" /></dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('cc'); ?>: </label></dt>
					<dd><input type="text" name="email_cc" value="<?php echo $this->mdl_mailer->form_value('email_cc'); ?>" /></dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('bcc'); ?>: </label></dt>
					<dd><input type="text" name="email_bcc" value="<?php echo $this->mdl_mailer->form_value('email_bcc'); ?>" /></dd>
				</dl>
				<dl>
					<dt><label>* <?php echo $this->lang->line('subject'); ?>: </label></dt>
					<dd><input type="text" name="email_subject" value="<?php echo $this->mdl_mailer->form_value('email_subject'); ?>" /></dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('invoice_as_body'); ?>: </label></dt>
					<dd><input type="checkbox" name="invoice_as_body" value="1" <?php if ($this->mdl_mcb_data->setting('default_email_body')) { ?>checked="checked"<?php } ?>/></dd>
				</dl>
				<dl>
					<dt><label><?php echo $this->lang->line('body'); ?>: </label></dt>
					<dd>
						<textarea name="email_body" rows="10" cols="60"><?php echo $this->mdl_mailer->form_value('email_body'); ?></textarea>
					</dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('footer'); ?>: </label></dt>
					<dd>
						<textarea name="email_footer" rows="10" cols="60"><?php echo $this->mdl_mailer->form_value('email_footer'); ?></textarea>
					</dd>
				</dl>

				<div style="float: right; margin-right: 5px;">
					<input class="uibutton" type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('send_email'); ?>" />
					<input class="uibutton" type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
				</div>
						
                <div style="clear: both;">&nbsp;</div>

			</form>

		</div>

	</div>

</div>

<?php $this->load->view('footer'); ?>