<?php

$config = array(
    'mcb_menu'  =>  array(
    	'dashboard' =>  array(
    			'title' =>  'dashboard',
    			'href'  =>  'dashboard'
    	),    		
		'contact'   =>  array(
		 	'title'     =>  'contacts',
		    'href'      =>  'contact/index',
		),
        'invoices'  =>  array(
            'title'     =>  'invoices & quotes',
            'href'      =>  'invoices/index',
        ),
        'inventory' =>  array(
            'title'         =>  'inventory',
            'href'          =>  'inventory/index',
        ),
        'reports'   =>  array(
            'title'         =>  'reports',
            'submenu'       =>  array(            		
            	'contacts_by_location'  =>  array(
            					'title' =>  'contacts by location',
            					'href'  =>  'contact/by_location'
            	),            	
                'client_statement'  =>  array(
                    'title' =>  'client statement',
                    'href'  =>  'reports/client_statement'
                ),
                'inventory_history' =>  array(
                    'title' =>  'inventory history',
                    'href'  =>  'reports/inventory_history'
                ),
                'inventory_sales'   =>  array(
                    'title' =>  'inventory sales',
                    'href'  =>  'reports/inventory_sales'
                )
            )
        ),
        'system'    =>  array(
            'title'         =>  'mcbsb settings',
            'href'          =>  'settings',
            'global_admin'  =>  TRUE,
            'submenu'       =>  array(
				'mcb_core_modules'   =>  array(
				                    'title'         =>  'handle modules',
				                    'href'          =>  'mcb_modules/core',
				                    'global_admin'  =>  TRUE,
				),                
                'fields'    =>  array(
                    'title'         =>  'custom fields',
                    'href'          =>  'fields/index',
                    'global_admin'  =>  TRUE,
                ),
            	'invoices/templates'   =>  array(
            		'title'         =>  'invoice templates',
            		'href'          =>  'templates/index/type/invoices'
            	),		 
                'invoices/invoice_groups'   =>  array(
                    'title'         =>  'invoice groups',
                    'href'          =>  'invoices/invoice_groups'
                ),
                'invoice_statuses'  =>  array(
                    'title'         =>  'invoice statuses',
                    'href'          =>  'invoice_statuses/index',
                    'global_admin'  =>  TRUE,
                ),
            	'payments/payment_methods'  =>  array(
            			'title'         =>  'payment methods',
            			'href'          =>  'payments/payment_methods',
            			'global_admin'  =>  TRUE
            	),            		
            	'templates/index/type/payment_receipts' =>  array(
            			'title'         =>  'payment receipt templates',
            			'href'          =>  'templates/index/type/payment_receipts',
            			'global_admin'  =>  TRUE
            	),            		
                'settings'  =>  array(
                    'title'         =>  'system settings',
                    'href'          =>  'settings/index',
                    'global_admin'  =>  TRUE,
                ),
                'tax_rates' =>  array(
                    'title'         =>  'tax rates',
                    'href'          =>  'tax_rates/index'
                ),
                'users' =>  array(
                    'title'         =>  'user accounts',
                    'href'          =>  'users/index',
                    'global_admin'  =>  TRUE,
                )
            )
        )
    )
);

?>
