<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo application_title(); ?></title>
        <link href="<?php echo base_url(); ?>assets/style/css/styles.css" rel="stylesheet" type="text/css" media="screen" />
        <!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/style/css/ie6.css" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/style/css/ie7.css" /><![endif]-->
		<link type="text/css" href="<?php echo base_url(); ?>assets/jquery/ui-themes/myclientbase/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.8.16.custom.min.js"></script>
    </head>
    <body>

        <?php $this->load->view('jquery_set_focus', array('id'=>'username')); ?>

        <div class="container_10" id="center_wrapper">

            <div class="grid_5 push_2" id="content_wrapper">

                <div class="section_wrapper">

                    <h3 class="title_black"><?php echo application_title() . ' ' . $this->lang->line('password_recovery'); ?></h3>

                    <?php $this->load->view('system_messages'); ?>

                    <div class="content toggle">

                        <p style="padding-left: 10px;"><?php echo $this->lang->line('recover_text'); ?></p>

                        <form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>">
                            
                            <dl>
                                <dt><label><?php echo $this->lang->line('username'); ?>: </label></dt>
                                <dd><input type="text" value="" id="username" name="username" /></dd>
                            </dl>
							<div style="float: right; margin-right: 5px;">
                            	<input class="uibutton" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
							</div>
                        </form>

                        <div style="clear: both;">&nbsp;</div>

                    </div>

                </div>

            </div>
        </div>


    </body>
</html>
