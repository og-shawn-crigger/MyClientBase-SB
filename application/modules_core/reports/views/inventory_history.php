<?php $this->load->view('header'); ?>

<script type="text/javascript">
	$(function() {

        $('#btn_submit').click(function() {

           var output_type = $('#output_type').val();

           if (output_type == 'view') {

               $('#results').load('<?php echo site_url('reports/inventory_history/jquery_display_results'); ?>' + '/' + output_type);

           }

           else {

               window.open('<?php echo site_url('reports/inventory_history/jquery_display_results'); ?>' + '/' + output_type);

           }
           

        });

	});
</script>

<div class="grid_11" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black"><?php echo $this->lang->line('inventory_history'); ?></h3>

		<div class="content toggle" style="min-height: 0px;">

			<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">

                <?php $this->load->view('partial_output_type'); ?>

				<input class="uibutton" style="float: right; margin-top: 10px; margin-right: 10px;" type="button" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('save'); ?>" />
			
			</form>
			
			<div style="clear: both;">&nbsp;</div>
		</div>

	</div>

    <div class="section_wrapper">
        <div class="content toggle no_padding" id="results">
        </div>
    </div>

</div>

<?php $this->load->view('footer'); ?>