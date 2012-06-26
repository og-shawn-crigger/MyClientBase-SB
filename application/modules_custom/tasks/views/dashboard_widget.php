	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('open_tasks'); ?></h3>

		<div class="content toggle no_padding" style="min-height: 0px;">

			<?php 
				$params = array(
					'limit'		=>	10,
					'paginate'	=>	FALSE,
					//'page'		=>	uri_assoc('page', 3)
				);
		
				$data = array(
					'tasks'					=>	$this->mdl_tasks->getAllOpen(), 
					//'show_task_selector'	=>	TRUE
				);
				
				$this->plenty_parser->parse('table.tpl', $data, false, 'smarty', 'tasks');
			?>

		</div>
		
	</div>