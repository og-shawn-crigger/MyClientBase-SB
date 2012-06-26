<?php $this->load->view('header', array('header_insert'=>'calendar/invoices_calendar_js')); ?>

<div class="grid_8" id="content_wrapper">

    <div class="section_wrapper">

		<h3 class="title_black"><?php echo (!uri_assoc('is_quote') ? $this->lang->line('invoices') : $this->lang->line('quotes')); ?></h3>

        <div class="content toggle">
          
          <div id='loading' style='display:none'>loading...</div>
          <div id='calendar'></div>

        </div>

    </div>

</div>

<!-- $actions_panel contains actions_panel.tpl -->
<?php echo $actions_panel; ?>

<?php $this->load->view('footer'); ?>