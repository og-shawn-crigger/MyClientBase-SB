<?php $this->load->view('header'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" style="display: inline;">

		<h3 class="title_black"><?php echo $this->lang->line('activities'); ?></h3>

		<div class="content toggle no_padding">

			<?php echo $table;	?>

		</div>

		</form>

	</div>

</div>

<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>