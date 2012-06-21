<?php $this->load->view('dashboard/header'); ?>

<div class="grid_11" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('core_modules'); ?></h3>

		<div class="content toggle no_padding">

			<table style="width: 100%;">

				<tr>
					<th scope="col" class="first"><?php echo $this->lang->line('name'); ?></th>
					<th scope="col"><?php echo $this->lang->line('description'); ?></th>
					<th scope="col"><?php echo $this->lang->line('version'); ?></th>
					<th scope="col"><?php echo $this->lang->line('author'); ?></th>
					<th scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
				</tr>

				<?php
					$counter = 1; 
					foreach ($modules as $module) { 
					$counter ++;
					$color='';
					if($counter % 2) $color='style="background-color: #e8e8e8;"'; 
				?>

				<tr valign="top" <?php echo $color; ?>>
					<td class="first"><b><?php if ($module->module_enabled) { echo anchor($module->module_path, $module->module_name); } else { echo $module->module_name; } ?></b></td>
					<td><?php echo $module->module_description; ?></td>
					<td>
						<?php if($module->module_version < $module->module_available_version) {
							echo anchor('mcb_modules/upgrade/' . $module->module_path, $module->module_version . ' - ' . $this->lang->line('upgrade') . ' (' . $module->module_available_version .')', array('style'=>'color: red; font-weight: bold;'));
						}
						else {
							echo $module->module_version;
						} ?>
					</td>
					<td><a href="<?php echo $module->module_homepage; ?>"><?php echo $module->module_author; ?></a></td>
					<td class="last">
					<?php 
					if($module->module_change_status == "1") {
						if($module->module_enabled) {
							echo anchor('mcb_modules/disable/' . $module->module_path, $this->lang->line('disable'));
						} else {
							echo anchor('mcb_modules/enable/' . $module->module_path, $this->lang->line('enable'));
						}
					} else {
						echo '--';
					} 
					?></td>
				</tr>

				<?php } ?>

			</table>

		</div>

	</div>

</div>

<?php $this->load->view('dashboard/footer'); ?>