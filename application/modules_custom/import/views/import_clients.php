<?php $this->load->view('dashboard/header'); ?>

<?php $this->load->view('dashboard/jquery_date_picker'); ?>

<div class="grid_7" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('client_import'); ?></h3>

        <?php $this->load->view('dashboard/system_messages'); ?>

		<div class="content toggle">

			<p><?php echo $this->lang->line('client_import_instructions'); ?></p>

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data">

                <dl>
                    <dt><label><?php echo $this->lang->line('user'); ?></label></dt>
                    <dd>
                        <select name="user_id">
                            <option value=""></option>
                            <?php foreach ($users as $user) { ?>
                            <option value="<?php echo $user->user_id; ?>"><?php echo $user->first_name . ' ' . $user->last_name; ?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>

                <?php foreach ($fields as $key=>$label) { ?>

                <dl>
                    <dt><label><?php echo $label; ?></label></dt>
                    <dd>
                        <select name="<?php echo $key; ?>">
                            <option value=""></option>
                            <?php $x = 0; foreach ($headers as $header) { ?>
                            <?php if ($header) { ?>
                            <option value="<?php echo $x; ?>"><?php echo $header; ?></option>
                            <?php } ?>
                            <?php $x++;} ?>
                        </select>
                    </dd>
                </dl>

                <?php } ?>

				<input type="submit" id="btn_submit" name="btn_import" value="<?php echo $this->lang->line('submit'); ?>" />

				<div style="clear: both;">&nbsp;</div>

			</form>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/sidebar', array('side_block'=>'invoices/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>