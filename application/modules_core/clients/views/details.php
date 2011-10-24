<?php $this->load->view('dashboard/header', array('header_insert'=>'clients/details_header')); ?>

<?php echo modules::run('invoices/widgets/generate_dialog'); ?>

<script type="text/javascript">
	$(function(){
		$('#tabs').tabs({ selected: <?php echo isset($tab_index) ? $tab_index : 0; ?> });
	});
</script>

<?php echo $middle; ?>

<?php $this->load->view('dashboard/footer'); ?>