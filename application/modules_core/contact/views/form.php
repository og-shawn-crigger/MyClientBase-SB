<?php $this->load->view('dashboard/header'); ?>

<script type="text/javascript">
	$(function(){
		$('#tabs').tabs({ selected: <?php echo isset($tab_index) ? $tab_index : 0; ?> });
	});
</script>

<?php $this->load->view('dashboard/jquery_clear_password'); ?>

<!-- $actions_panel contains form.tpl -->
<?php echo $form; ?>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php //$this->load->view('dashboard/sidebar', array('side_block'=>'contact/sidebar')); ?>

<?php $this->load->view('dashboard/footer'); ?>