<?php $this->load->view('header'); ?>

<?php $this->load->view('jquery_date_picker'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('activity_form'); ?></h3>

		<div class="content toggle" style="min-height: 0; padding-bottom: 45px;">
				
			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">
				<input id="id" type="hidden" name="id" value="<?php echo $activity->id; ?>" />


			<?php 
				if(isset($task) && is_object($task)) {
			?>
				<input id="task_id" type="hidden" name="task_id" value="<?php echo $task->task_id; ?>" />
				<input id="client_id" type="hidden" name="client_id" value="<?php echo $task->client_id; ?>" />
				
				<h3 style="margin-left: 0px; padding-left: 5px; padding-top: 0px;"><?php echo $this->lang->line('task_summary');?></h3>
				<dl>
					<dt><label><?php echo $this->lang->line('contact');?>: </label></dt>
					<dd><b><?php echo ucwords($task->client_name); ?></b></dd>

					<dt><label><?php echo $this->lang->line('task');?>: </label></dt>
					<dd><b><?php echo $task->title; ?></b></dd>

					<dt><label><?php echo $this->lang->line('description');?>: </label></dt>
					<dd><?php echo $task->description; ?></dd>
					
					<p>
						| <?php echo $this->lang->line('start_date');?>: <?php echo format_date($task->start_date); ?> | 
						<?php echo $this->lang->line('due_date');?>: <?php echo format_date($task->due_date); ?> |
						<?php echo $this->lang->line('complete_date');?>: <?php echo format_date($task->complete_date); ?> |
					</p>
				</dl>				
								
			<?php } ?>
			
				<h3 style="margin-left: 0px; padding-left: 5px; padding-top: 15px;"><?php echo $this->lang->line('add_activity');?></h3>
				
				<dl>
					<dt><label><?php echo $this->lang->line('date');?>: </label></dt>
					<dd><input class="datepicker" type="text" name="date" value="<?php echo format_date($activity->date); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('description');?>: </label></dt>
					<dd><textarea id="description" name="description" rows="10" cols="72"><?php echo $activity->description; ?></textarea></dd>
				</dl>
				
				<dl>
					<dt><label><?php echo $this->lang->line('duration');?>: </label></dt>
					<dd><input id="duration" type="text" name="duration" value="<?php echo $activity->duration; ?>" /></dd>
				</dl>				
				
				<dl>
					<dt><label><?php echo $this->lang->line('mileage');?>: </label></dt>
					<dd><input id="mileage" type="text" name="mileage" value="<?php echo $activity->mileage; ?>" /></dd>
				</dl>
				
				<div style="float: right; margin-right: 5px; margin-top: 5px;">
					<input class="uibutton" type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('save');?>" />
					<input class="uibutton" type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel');?>" />
				</div>
				
			</form>
	
		</div>
				
	</div>

</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>