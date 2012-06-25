<?php $this->load->view('header'); ?>

<?php $this->load->view('jquery_tasks'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" style="display: inline;">

		<h3 class="title_black"><?php echo $this->lang->line('tasks'); ?>
			<input type="submit" id="btn_create_mti" name="btn_create_mti" style="float: right; margin-top: 10px; margin-right: 10px; display: none;" value="<?php echo $this->lang->line('create_mti'); ?>" />
		</h3>

		<div class="content toggle no_padding">

			<?php //$this->load->view('table');
				echo $table;
			?>

		</div>

		</form>

	</div>

</div>

<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>