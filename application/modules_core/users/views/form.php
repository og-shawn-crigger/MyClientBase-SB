<?php $this->load->view('header'); ?>

<script type="text/javascript">
	$(function(){
		$('#tabs').tabs({ selected: <?php echo isset($tab_index) ? $tab_index : 0; ?> });
	});
</script>

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('user_account_form'); ?></h3>

		<div class="content toggle">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

				<div id="tabs" style="padding-bottom: 45px;">

					<ul>
						<li><a href="#tab_details"><?php echo $this->lang->line('details'); ?></a></li>
						<?php
						//TODO  
                        //<li><a href="#tab_settings"><?php echo $this->lang->line('settings');</a></li>
                        ?>
					</ul>

					<div id="tab_details">
						<?php $this->load->view('form_tab_details'); ?>						
					</div>

                    <div id="tab_settings">
                        <?php //TODO $this->load->view('form_tab_settings'); ?>
                    </div>

					<div style="margin-top: 0px; float: right; margin-right: 30px; margin-top: 0px;">
						<input class="uibutton" type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('save'); ?>" />
						<input class="uibutton" type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
					</div>    						
                    
				</div>

            	<div style="clear: both;">&nbsp;</div>

			</form>

		</div>

	</div>

</div>

<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>