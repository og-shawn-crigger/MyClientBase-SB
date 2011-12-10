<?php $this->load->view('dashboard/header'); ?>

<?php $this->load->view('dashboard/jquery_date_picker'); ?>

<div class="grid_10" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('enter_deposit'); ?></h3>

		<?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle">

		<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

			<dl>
				<dt><label>* <?php echo $this->lang->line('client'); ?>: </label></dt>
				<dd>
					<select name="client_credit_client_id" id="client_credit_client_id">
						<option value=""></option>
						<?php foreach ($clients as $client) { ?>
						<option value="<?php echo $client->client_id; ?>" <?php if ($this->mdl_client_credits->form_value('client_credit_client_id') == $client->client_id) { ?>selected="selected"<?php } ?>><?php echo $client->client_name; ?></option>
						<?php } ?>
					</select>
				</dd>
			</dl>

			<dl>
				<dt><label>* <?php echo $this->lang->line('amount'); ?>: </label></dt>
				<dd><input type="text" name="client_credit_amount" value="<?php echo format_number($this->mdl_client_credits->form_value('client_credit_amount')); ?>" /></dd>
			</dl>

			<dl>
				<dt><label>* <?php echo $this->lang->line('date'); ?>: </label></dt>
				<dd><input type="text" name="client_credit_date" class="datepicker" value="<?php echo $this->mdl_client_credits->form_value('client_credit_date'); ?>" /></dd>
			</dl>

			<dl>
				<dt><label><?php echo $this->lang->line('note'); ?>: </label></dt>
				<dd><textarea name="client_credit_note" rows="5" cols="40"><?php echo $this->mdl_client_credits->form_value('client_credit_note'); ?></textarea></dd>
			</dl>

            <div style="clear: both;">&nbsp;</div>

			<input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
			<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />

        </form>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>