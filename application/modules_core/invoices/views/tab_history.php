<table style="width: 100%;">
	<tr>
		<th style="width: 20%; text-align: left;"><?php echo $this->lang->line('date'); ?></th>
		<th style="width: 50%; text-align: left;"><?php echo $this->lang->line('history'); ?></th>
	</tr>
	<?php foreach ($history as $history_item) { ?>
	<tr>
		<td style="text-align: left;"><?php echo format_date($history_item->invoice_history_date,true); ?></td>
		<td style="text-align: left;"><?php echo ucwords($history_item->username)." ".strtolower($history_item->invoice_history_data); ?></td>
	</tr>
		<?php } ?>
</table>


	<div style="clear: both;">&nbsp;</div>