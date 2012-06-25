<?php $this->load->view('header'); ?>

<?php $this->load->view('jquery_date_picker'); ?>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('task_form'); ?></h3>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">
				<input id="task_id" type="hidden" name="task_id" value="<?php echo $task->task_id; ?>" />
				<dl>
					<dt><label><?php echo $this->lang->line('client');?>: </label></dt>
					<dd>
						<input id="client_id" type="hidden" name="client_id" value="<?php echo $task->client_id; ?>" />
						<input id="client_id_key" type="hidden" name="client_id_key" value="<?php echo $task->client_id_key; ?>" />
						<input id="client_name" type="text" name="client_name" value="<?php echo $task->client_name; ?>" readonly />
					</dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('start_date');?>: </label></dt>
					<dd><input class="datepicker" type="text" name="start_date" value="<?php echo format_date($task->start_date); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('due_date');?>: </label></dt>
					<dd><input class="datepicker" type="text" name="due_date" value="<?php echo format_date($task->due_date); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('complete_date');?>: </label></dt>
					<dd><input class="datepicker" type="text" name="complete_date" value="<?php echo format_date($task->complete_date); ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('title');?>: </label></dt>
					<dd><input id="title" type="text" name="title" value="<?php echo $task->title; ?>" /></dd>
				</dl>

				<dl>
					<dt><label><?php echo $this->lang->line('description');?>: </label></dt>
					<dd><textarea id="description" name="description" rows="10" cols="50"><?php echo $task->description; ?></textarea></dd>
				</dl>

				<input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('submit');?>" />
				<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel');?>" />

			</form>

		</div>

	</div>

</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>