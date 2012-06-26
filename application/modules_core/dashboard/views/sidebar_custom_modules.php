<?php if ($this->session->userdata('global_admin') and $this->mdl_mcb_modules->num_custom_modules_enabled) { ?>

<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">

	<h3 class="title_black" style="width: 280px;"><?php echo $this->lang->line('custom_modules'); ?></h3>
	<ul class="quicklinks content toggle" style="width: 280px;">
		<?php $x = 0; foreach ($this->mdl_mcb_modules->custom_modules as $module) {;?>
			<?php if ($module->module_enabled) { $x++;?>
				<li <?php if ($x == $this->mdl_mcb_modules->num_custom_modules_enabled) { ?>class="last"<?php } ?>>
				<?php echo anchor($module->module_path, $module->module_name); ?>
				</li>
			<?php } ?>
		<?php } ?>
	</ul>

</div>

<?php } ?>