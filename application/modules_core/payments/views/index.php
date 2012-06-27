<?php $this->load->view('header'); ?>

<?php echo modules::run('payments/payment_widgets/generate_dialog'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('payments'); ?>
			<?php 
				if(isset($tot_num_payments)) {
					?>
					<span style="font-size: 11px; font-color: white; padding-left: 5px;">( <?php echo $this->lang->line('found') . ' ' . $tot_num_payments; ?> )</span>
					<?php 
				}
			?>		
		</h3>

		<div class="content toggle no_padding">

			<?php $this->load->view('table'); ?>

		</div>

	</div>

</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>