<?php

$config = array(
    'mcb_menu'  =>  array(
		'contact'   =>  array(
		 	'title'     =>  'contacts',
		    'href'      =>  'contact/index',
		),
    	/*    		       
    	'assignments'   =>  array(
    		'title'     =>  'assignments',
    		'href'      =>  'assignments/index',
    	),
    	*/    		
        'invoices'  =>  array(
            'title'     =>  'invoices&quotes',
            'href'      =>  'invoices/index',
        ),
        'inventory' =>  array(
            'title'         =>  'inventory',
            'href'          =>  'inventory/index',
        ),
        'reports'   =>  array(
            'title'         =>  'reports',
            'submenu'       =>  array(
            	/*
				'dashboard' =>  array(
				            'title' =>  'dashboard',
				            'href'  =>  'dashboard'
				),            
                'client_list'   =>  array(
                    'title' =>  'client_list',
                    'href'  =>  'reports/client_list'
                ),
                */
            		
            	'contacts_by_location'  =>  array(
            					'title' =>  'contacts_by_location',
            					'href'  =>  'contact/by_location'
            	),            	
                'client_statement'  =>  array(
                    'title' =>  'client_statement',
                    'href'  =>  'reports/client_statement'
                ),
                'inventory_history' =>  array(
                    'title' =>  'inventory_history',
                    'href'  =>  'reports/inventory_history'
                ),
                'inventory_sales'   =>  array(
                    'title' =>  'inventory_sales',
                    'href'  =>  'reports/inventory_sales'
                )
            )
        ),
        'system'    =>  array(
            'title'         =>  'mcbsb_settings',
            'href'          =>  'settings',
            'global_admin'  =>  TRUE,
            'submenu'       =>  array(
				'mcb_core_modules'   =>  array(
				                    'title'         =>  'core_modules',
				                    'href'          =>  'mcb_modules/core',
				                    'global_admin'  =>  TRUE,
				),                
                'mcb_modules'   =>  array(
                    'title'         =>  'custom_modules',
                    'href'          =>  'mcb_modules/index',
                    'global_admin'  =>  TRUE,
                ),
                'fields'    =>  array(
                    'title'         =>  'custom_fields',
                    'href'          =>  'fields/index',
                    'global_admin'  =>  TRUE,
                ),
            	'invoices/templates'   =>  array(
            		'title'         =>  'invoice_templates',
            		'href'          =>  'templates/index/type/invoices'
            	),		 
                'invoices/invoice_groups'   =>  array(
                    'title'         =>  'invoice_groups',
                    'href'          =>  'invoices/invoice_groups'
                ),
                'invoice_statuses'  =>  array(
                    'title'         =>  'invoice_statuses',
                    'href'          =>  'invoice_statuses/index',
                    'global_admin'  =>  TRUE,
                ),
            	'payments/payment_methods'  =>  array(
            			'title'         =>  'payment_methods',
            			'href'          =>  'payments/payment_methods',
            			'global_admin'  =>  TRUE
            	),            		
            	'templates/index/type/payment_receipts' =>  array(
            			'title'         =>  'payment_receipt_templates',
            			'href'          =>  'templates/index/type/payment_receipts',
            			'global_admin'  =>  TRUE
            	),            		
                'settings'  =>  array(
                    'title'         =>  'system_settings',
                    'href'          =>  'settings/index',
                    'global_admin'  =>  TRUE,
                ),
                'tax_rates' =>  array(
                    'title'         =>  'tax_rates',
                    'href'          =>  'tax_rates/index'
                ),
                'users' =>  array(
                    'title'         =>  'user_accounts',
                    'href'          =>  'users/index',
                    'global_admin'  =>  TRUE,
                )
            )
        )
    )
);

?>
