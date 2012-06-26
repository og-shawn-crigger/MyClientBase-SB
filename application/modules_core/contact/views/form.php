<?php $this->load->view('header'); ?>

<script type="text/javascript">
	$(function(){
		$('#tabs').tabs({ selected: <?php echo isset($tab_index) ? $tab_index : 0; ?> });
	});
</script>

<?php $this->load->view('jquery_clear_password'); ?>

<!-- $actions_panel contains form.tpl -->
<?php echo $form; ?>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>