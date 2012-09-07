DELETE FROM `mcb_data` WHERE `mcb_data`.`mcb_data_id` = 31;

ALTER TABLE `mcb_invoices` DROP `invoice_custom_1`;

ALTER TABLE  `mcb_tax_rates` CHANGE  `tax_rate_percent`  `tax_rate_percent` DECIMAL( 5, 2 ) NOT NULL;

ALTER TABLE  `mcb_users` CHANGE  `client_id`  `user_client_id` INT( 11 ) NOT NULL DEFAULT  '0';
