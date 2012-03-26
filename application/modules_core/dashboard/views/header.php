<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo application_title(); ?></title>
		<link href="<?php echo base_url(); ?>assets/style/css/styles.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="<?php echo base_url(); ?>assets/style/css/superfish.css" rel="stylesheet" type="text/css" media="screen" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/style/css/ie6.css" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/style/css/ie7.css" /><![endif]-->
		<link type="text/css" href="<?php echo base_url(); ?>assets/jquery/ui-themes/myclientbase/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
		<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-1.6.2.min.js"></script> -->
		<?php 
			//jquery full version is more comfortable for debugging but heavier than the .min version: so let's use one or the other
			//depending on the environment we are using 
			if(ENVIRONMENT == 'production'){
				//echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>';
				echo '<script type="text/javascript" src="'.base_url().'assets/jquery/jquery-1-7-1.min.js"></script>';
			} else {
				//echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>'; 
				echo '<script type="text/javascript" src="'.base_url().'assets/jquery/jquery-1-7-1.js"></script>';
				?>
				
				<script type="text/javascript">
				
					//DAM This rewrites all the PHP errors at the bottom of the page and hides the original ones
					$(document).ready(function(){
					
						var html = '';
						jQuery('.php_error').each(function(index){
							html = html + '<div class="php_error" style="border:1px solid #990000; padding-left:20px; margin:0 0 10px 0;"> [error #' + index + '] '+ jQuery(this).html() + '</div>';
							jQuery(this).replaceWith("");
						});
					
						if(html != "") html = '<br/><div id="php_error_container" style=""><h1>List of PHP errors</h1>' + html + '</div>';
						jQuery('.php_error').remove();
							
						jQuery('#php_error_container').replaceWith(html);
						jQuery('.php_error').css('background-color','yellow');
					});
				
				</script>				
			<?php 
			}
		?>
		
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-ui-1.8.16.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/jquery/jquery.maskedinput-1.2.2.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>assets/jquery/util.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>assets/jquery/superfish.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>assets/jquery/supersubs.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>assets/jquery/mcbsb.js" type="text/javascript"></script>
		
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	
		
        <script>

            $(document).ready(function(){
                $("ul.sf-menu").supersubs({
                    minWidth:    12,
                    maxWidth:    38,
                    extraWidth:  1
                }).superfish();

				$( "input:submit.uibutton").button();

            });

        </script>          
		<?php if (isset($header_insert)) { if (!is_array($header_insert)) { $this->load->view($header_insert); } else { foreach ($header_insert as $insert) { $this->load->view($insert); } } } ?>		
	</head>
	<body>
	<!-- top anchor -->
	<a id="top" name="top"></a>
		<!-- No header - saving space 
		<div id="header_wrapper">

			<div class="container_10" id="header_content">

				<h1><?php echo application_title(); ?></h1>

			</div>

		</div>
 		-->
 		
		<div id="navigation_wrapper">

			<ul class="sf-menu" id="navigation">

                <?php 
                	echo modules::run('mcb_menu/header_menu/display', array('view'=>'dashboard/header_menu')); 
                ?>

			</ul>

		</div>

		<div class="container_10" id="center_wrapper">