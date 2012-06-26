<?php $this->load->view('header', array('header_insert'=>'contact/details_header')); ?>

<?php //echo modules::run('invoices/widgets/generate_dialog'); ?>

<script type="text/javascript">
	$(function(){
		$('#tabs').tabs({ selected: <?php echo isset($tab_index) ? $tab_index : 0; ?> });
	});
</script>


<!-- $details contains details.tpl -->
<div class="grid_8" id="content_wrapper">
<?php echo $details; ?>
</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>