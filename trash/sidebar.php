<div class="section_wrapper">

	<h3 class="title_black"><?php echo $this->lang->line('action_panel'); ?></h3>

	<ul class="quicklinks content toggle">
		<li><?php echo anchor('contact', $this->lang->line('view_clients')); ?></li>
		<li><?php echo anchor('contact', $this->lang->line('create_quote')); ?></li>
		<li><?php echo anchor('contact', $this->lang->line('create_invoice')); ?></li>
		<li><?php echo anchor('contact', $this->lang->line('Show quotes')); ?></li>
		<li><?php echo anchor('contact', $this->lang->line('Show invoices')); ?></li>
		<li class="last"><?php echo anchor('contact', $this->lang->line('add_client')); ?></li>
		<!-- 
		<li>
				<form method="post" action="" style="display: inline;">
				<input type="submit" name="btn_edit_client" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='edit_client'}" />
                <input type="submit" name="btn_add_invoice" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='create_invoice'}" />
				<input type="submit" name="btn_add_quote" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='create_quote'}" />
				</form>
		</li>
		 -->						
	</ul>

</div>